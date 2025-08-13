<?php

namespace App\Services\Payments;


use App\Traits\Billing;
use App\Models\Websystem;
use App\Traits\GenerateName;
use Illuminate\Support\Carbon;
use App\Models\Billings\Payment;
use App\Models\Customers\AutoIsolir;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Services\Mikrotiks\MikrotikIpStaticService;

class MikrotikPaymentService
{
    use GenerateName;
    use Billing;

    private MikrotikPppService $pppService;
    private MikrotikIpStaticService $ipStaticService;
    public function __construct()
    {
        $this->pppService = new MikrotikPppService;
        $this->ipStaticService = new MikrotikIpStaticService;
    }

    /**
     * Update mikrotik
     * Summary of mikrotik_payment_process
     * @param mixed $payment
     * @return array{message: mixed, success: bool|array{message: string, success: bool}|array{success: bool}}
     */
    public function mikrotik_payment_process($payment)
    {
        $mikrotik = $payment->invoice->customer_paket->paket->mikrotik;
        $autoIsolir = AutoIsolir::where('mikrotik_id', $mikrotik->id)->first();

        if ($autoIsolir && !$autoIsolir->disabled) {
            switch ($payment->invoice->customer_paket->internet_service->value) {
                case 'ppp':
                    return $this->update_payment_secret_ppp_on_mikrotik($payment, $mikrotik);
                case 'ip_static':
                    return $this->update_payment_ip_static_on_mikrotik($payment, $mikrotik, $autoIsolir);
                default:
                    return [
                        'success' => false,
                        'message' =>  'Unsupported internet service.'
                    ];
            }
        }
        return ['success' => true];
    }

    /**
     * Summary of mikrotik_unpayment_process
     * @param mixed $invoice
     * @return array{message: mixed, success: bool|array{message: string, success: bool}|array{success: bool}}
     */
    public function mikrotik_unpayment_process($payment)
    {
        $mikrotik = $payment->invoice->customer_paket->paket->mikrotik;
        $autoIsolir = AutoIsolir::where('mikrotik_id', $mikrotik->id)->first();
        if ($autoIsolir && !$autoIsolir->disabled) {
            switch ($payment->invoice->customer_paket->internet_service->value) {
                case 'ppp':
                    return $this->update_unpayment_secret_ppp_on_mikrotik($payment, $mikrotik, $autoIsolir);
                case 'ip_static':
                    return $this->update_unpayment_ip_static_on_mikrotik($payment, $mikrotik, $autoIsolir);
                default:
                    return [
                        'success' => false,
                        'message' =>  'Unsupported internet service.'
                    ];
            }
        }

        return ['success' => true];
    }

    /**
     * Summary of update_payment_secret_ppp_on_mikrotik
     * @param mixed $payment
     * @param mixed $mikrotik
     * @return array{message: mixed, success: bool|array{message: string, success: bool}}
     * Update comment user secret if profile on mikrotik and profile on cm is the same
     * Update comment and profile user secret if profile mikrotik != user profile on cm
     */
    public function update_payment_secret_ppp_on_mikrotik($payment, $mikrotik)
    {
        $customerPppPaket = $payment->invoice->customer_paket->customer_ppp_paket;
      //  $username = $customerPppPaket->username;
        $profileName = $payment->invoice->customer_paket->paket->paket_profile->profile_name;
        $userSecretResponse = $this->pppService->getPppSecret($mikrotik, $customerPppPaket);

        if ($userSecretResponse['success']) {
            $userSecret = $userSecretResponse['result'];
            $isolirDriverMikrotik = Websystem::first()->isDriverMikrotik();

            if ($payment->payment_method === 'paylater') {
                $expiredDate = $payment->paylater_date ? (Carbon::parse($payment->invoice->customer_paket->expired_date)->gt(Carbon::parse($payment->paylater_date)) ? $payment->invoice->customer_paket->expired_date : $payment->paylater_date) : $payment->invoice->customer_paket->expired_date;
            } else {
                $expiredDate = $payment->invoice->end_periode;
            }
            $comment = $isolirDriverMikrotik ? $this->commentMikrotik($expiredDate) : null;

            if ($userSecret['profile'] == $profileName) {
                if ($isolirDriverMikrotik) $this->pppService->updateCommentSecret($mikrotik, $customerPppPaket, $comment);
            } else {
                $response = $this->pppService->updateProfileSecret($mikrotik, $customerPppPaket, $profileName, $comment);
                if (!$response['success']) return $response;
            }
            return ['success' => true];
        }
        return $userSecretResponse;
    }

    public function update_unpayment_secret_ppp_on_mikrotik($payment, $mikrotik, $autoIsolir)
    {
        $username = $payment->invoice->customer_paket->customer_ppp_paket->username;
        $customerPppPaket = $payment->invoice->customer_paket->customer_ppp_paket;
        $userSecretResponse = $this->pppService->getPppSecret($mikrotik, $customerPppPaket);
        if ($userSecretResponse['success']) {
            $lastExpiredDate = Carbon::parse($payment->invoice->start_periode)->startOfDay();
            $previousPayment = Payment::whereInvoiceId($payment->invoice_id)->where('id', '<', $payment->id)->orderby('id', 'desc')->first();
            $expiredDate = $previousPayment ? ($previousPayment->payment_method === 'paylater' ? $previousPayment->paylater_date : $lastExpiredDate) : $lastExpiredDate;

            $profileIsolir = $autoIsolir->profile_id;
            $isolirDriverMikrotik = Websystem::first()->isDriverMikrotik();
            $comment = $isolirDriverMikrotik ? $this->commentMikrotik($expiredDate) : null;

            if ($lastExpiredDate->isPast()) {
                $response = $this->pppService->updateProfileSecret($mikrotik, $customerPppPaket, $profileIsolir, $comment);
                if (!$response['success']) return $response;
            } else {
                if ($isolirDriverMikrotik) $this->pppService->updateCommentSecret($mikrotik, $customerPppPaket, $comment);
            }
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'User secret on mikrotik ' . $mikrotik->name . ' not found'];
    }

    public function update_payment_ip_static_on_mikrotik($payment, $mikrotik, $autoIsolir)
    {
        $ipAddress = $payment->invoice->customer_paket->customer_static_paket->ip_address;
        $address_list_customer = $this->ipStaticService->getAddressListByIpAddress($mikrotik, $ipAddress);

        if (!is_null($address_list_customer)) {
            if ($address_list_customer[0]['list'] == $autoIsolir->address_list_isolir) {
                $this->ipStaticService->deleteIpFromAddressList($mikrotik, $ipAddress,  $autoIsolir->address_list_isolir);
            }
        }

        $isolirDiriverMikrotik = Websystem::first()->isDriverMikrotik();
        if ($isolirDiriverMikrotik) {
            if ($payment->payment_method === 'paylater') {
                $expiredDate = $payment->paylater_date ? (Carbon::parse($payment->invoice->customer_paket->expired_date)->gt(Carbon::parse($payment->paylater_date)) ? $payment->invoice->customer_paket->expired_date : $payment->paylater_date) : $payment->invoice->customer_paket->expired_date;
            } else {
                $expiredDate = $payment->invoice->end_periode;
            }
            $comment = $isolirDiriverMikrotik ? $this->commentMikrotik($expiredDate) : null;

            $response = $this->ipStaticService->updateCommentSimpleQueue($mikrotik, $ipAddress, $comment);
            if ($response === null) return ['success' => false, 'message' => 'IP address not found.'];
            $response = $this->ipStaticService->updateCommentArp($mikrotik, $ipAddress, $comment);
            if ($response === null) return ['success' => false, 'message' => 'IP address not found.'];
        }

        return [
            'success' => true
        ];
    }

    public function update_unpayment_ip_static_on_mikrotik($payment, $mikrotik, $autoIsolir)
    {
        $ipAddress = $payment->invoice->customer_paket->customer_static_paket->ip_address;
        $lastExpiredDate = Carbon::parse($payment->invoice->start_periode)->startOfDay();
        $previousPayment = Payment::whereInvoiceId($payment->invoice_id)->where('id', '<', $payment->id)->orderby('id', 'desc')->first();
        $expiredDate = $previousPayment ? ($previousPayment->payment_method === 'paylater' ? $previousPayment->paylater_date : $lastExpiredDate) : $lastExpiredDate;

        if ($lastExpiredDate->isPast()) {
            $this->ipStaticService->addIpToAddressList($mikrotik, $ipAddress, $autoIsolir->address_list_isolir);
        }

        $isolirDiriverMikrotik = Websystem::first()->isDriverMikrotik();
        if ($isolirDiriverMikrotik) {
            $comment = $isolirDiriverMikrotik ? $this->commentMikrotik($expiredDate) : null;
            $this->ipStaticService->updateCommentSimpleQueue($mikrotik, $ipAddress, $comment);
            $this->ipStaticService->updateCommentArp($mikrotik, $ipAddress, $comment);
        }
        return [
            'success' => true
        ];
    }

    private function commentMikrotik($expiredDate)
    {
        $websystem = Websystem::first();
        $commentUnpayment = $websystem->comment_unpayment;
        $comment = $commentUnpayment . '_' . Carbon::parse($expiredDate)->format('d_m_Y');
        return $comment;
    }
}
