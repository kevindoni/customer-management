<?php

namespace App\Services;

use App\Models\User;
use App\Models\Websystem;
use App\Models\Pakets\Paket;
use App\Models\Pakets\PppType;
use Exception;
use Illuminate\Support\Carbon;
use App\Models\Customers\AutoIsolir;
use App\Models\Customers\CustomerPaket;
use App\Services\Billings\BillingService;
use App\Livewire\Actions\Users\UserAction;
use App\Models\Customers\CustomerPppPaket;
use App\Services\Billings\DeadlineService;
use App\Models\Customers\CustomerStaticPaket;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Services\Mikrotiks\MikrotikIpStaticService;
use App\Livewire\Actions\Customers\CustomerPaketAction;
use App\Livewire\Actions\Customers\CustomerPaketAddressAction;

class CustomerPaketService
{
    protected $pricingService, $deadlineService;
    private $mikrotikPppService;

    private $generalLogServices;


    public function __construct(
        DeadlineService $deadlineService = null,
        MikrotikPppService $mikrotikPppService = null,
        GeneralLogServices $generalLogServices = null
    ) {
        $this->deadlineService = $deadlineService ?? new DeadlineService();
        $this->mikrotikPppService = $mikrotikPppService ?? new MikrotikPppService();
        $this->generalLogServices = $generalLogServices ?? new GeneralLogServices;
    }

    /**
     * Summary of addCustomerPaket
     * @param \App\Models\User $user
     * @param mixed $input
     * @return CustomerPaket
     */
    public function addCustomerPaket(User $user, $input)
    {

        $paket = Paket::find($input['selectedPaket']);
        $startDate = Carbon::now();
        $expiredDate = $paket->trial_days > 0 ? Carbon::now()->addDays($paket->trial_days) : Carbon::now();

        //Create Customer Paket
        $customerPaket = (new CustomerPaketAction())->createCustomerPaket($user, $paket, $startDate, $expiredDate, $input);

        //Create invoice first time
        (new BillingService())->generateInvoice($customerPaket);
        return $customerPaket;
    }


    public function createCustomerPaket(CustomerPaket $customerPaket)
    {
        $mikrotik = $customerPaket->paket->mikrotik;
        $comment = $this->commentMikrotik($customerPaket->expired_date);

        try {
            if ($customerPaket->isPpp()) {
                //PPP Service
                return $this->create_ppp_secret($mikrotik, $customerPaket, $comment, 'false');
            } else {
                //IP Static
                return $this->create_simpleque_ip_static($mikrotik, $customerPaket, $comment);
            }
        } catch (Exception $e) {
            // Log error untuk debugging
            \Log::error('Error creating customer paket: ' . $e->getMessage(), [
                'customer_paket_id' => $customerPaket->id ?? 'unknown',
                'user_id' => $customerPaket->user_id ?? 'unknown',
                'paket_id' => $customerPaket->paket_id ?? 'unknown',
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // $this->delete_user_on_mikrotik($customerPaket);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat paket customer: ' . $e->getMessage()
            ];
        }
    }

    public function activationCustomerPaket(CustomerPaket $customerPaket)
    {
        if ($customerPaket->status == 'cancelled') (new BillingService())->generateInvoice($customerPaket);
        return $this->createCustomerPaket($customerPaket);
    }

    public function editActivationDate(CustomerPaket $customerPaket, $newActivationDate)
    {
        /*$intervalDayInvoice = Websystem::first()->different_day_create_billing;
        $nowDate = Carbon::now();
        $dateAactivation = Carbon::parse($newActivationDate)->format('d');
        $startDate = $nowDate->setDay((int)$dateAactivation)->startOfDay();
        $expiredDate = Carbon::parse($startDate)->add($customerPaket->getRenewalPeriod());
        $nexBilledAt = Carbon::parse($expiredDate)->subDays($intervalDayInvoice);

        $customerPaket->forceFill([
            'start_date' => $startDate,
            'activation_date' => $newActivationDate ?? $customerPaket->activation_date,
            'expired_date' => $expiredDate,
            'next_billed_at' => $nexBilledAt
        ])->save();

        $renewalPeriod = $customerPaket->getRenewalPeriod();
        $nextBilledAt = Carbon::parse($customerPaket->next_billed_at)->add($renewalPeriod);
        $invoicePeriod = Carbon::parse($nextBilledAt)->add($renewalPeriod)->startOfMonth();
        if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
            (new BillingService())->generateInvoice($customerPaket);
        }*/

        $webSystem = Websystem::first();
        $subscriptionMode = $webSystem->subscription_mode;
        $intervalDayInvoice = $webSystem->different_day_create_billing;
        // $nowDate = Carbon::now();
        $dateAactivation = Carbon::parse($newActivationDate)->format('d');

        // $customerPaketStartDate = $nowDate->setDay((int)$dateAactivation)->startOfDay();
        // $customerPaketExpiredDate = Carbon::parse($customerPaketStartDate)->add($customerPaket->getRenewalPeriod());
        // $nextBilledAt = Carbon::parse($expiredDate)->subDays($intervalDayInvoice);

        $latestInvoice = $customerPaket->invoices()->latest()->first();
        $renewalPeriod = $customerPaket->getRenewalPeriod();

        $expiredDate = Carbon::parse($newActivationDate)->add($renewalPeriod);
        $customerPaket->forceFill([
            'start_date' => $newActivationDate,
            'activation_date' => $newActivationDate,
            'expired_date' => $expiredDate,
            'next_billed_at' => Carbon::parse($expiredDate)->subDays($intervalDayInvoice)
        ])->save();

        if ($latestInvoice && $latestInvoice->status != 'paid') {
            $startMonthPeriode = Carbon::parse($latestInvoice->periode)->format('m');
            $endMonthPeriode = Carbon::parse($latestInvoice->periode)->add($renewalPeriod)->format('m');
            $startPeriode = Carbon::parse($latestInvoice->start_periode)->setDay((int)$dateAactivation)->setMonth((int)$startMonthPeriode);
            $endPeriode = Carbon::parse($latestInvoice->end_periode)->setDay((int)$dateAactivation)->setMonth((int)$endMonthPeriode);

            $latestInvoice->forceFill([
                'start_periode' => $startPeriode,
                'end_periode' => $endPeriode,
                'due_date' => $subscriptionMode === 'pascabayar' ?  $endPeriode : $startPeriode,
            ])->save();

            $diffMonths = Carbon::parse($latestInvoice->periode)->diffInMonths(Carbon::now());

            for ($i = 0; $i <= (int)$diffMonths; $i++) {
                $latestInvoice = $customerPaket->invoices()->latest()->first();
                $invoiceNextPeriod = Carbon::parse($latestInvoice->periode)->add($renewalPeriod);
                $nextBilledAt = Carbon::parse($latestInvoice->end_periode)->subDays($intervalDayInvoice);
                if ($customerPaket->needsBilling($invoiceNextPeriod, $nextBilledAt)) {
                    (new BillingService())->generateInvoice($customerPaket);
                }
            }
        } else {
            // dd(Carbon::parse($newActivationDate)->isFuture());
            //   if (Carbon::parse($newActivationDate)->isFuture()) {
            //   } else {

            //   }
            //} else if (Carbon::parse($customerPaketStartDate)->isFuture()) {
            // $customerPaketExpiredDate = $customerPaketStartDate;
            // $customerPaketStartDate = Carbon::parse($customerPaketStartDate)->sub($customerPaket->getRenewalPeriod());
            //  $nextBilledAt = Carbon::parse($customerPaketExpiredDate)->subDays($intervalDayInvoice);



            $diffMonths = Carbon::parse($newActivationDate)->diffInMonths(Carbon::now());
            /// dd((int)$diffMonths);
            $invoicePeriod = Carbon::parse($newActivationDate)->startOfMonth();
            $nextBilledAt = Carbon::parse($newActivationDate)->startOfMonth();
            for ($i = 0; $i <= (int)$diffMonths; $i++) {
                if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
                    (new BillingService())->generateInvoice($customerPaket);
                }
                // $latestInvoice = $customerPaket->invoices()->latest()->first();
                $latestInvoice = $customerPaket->invoices()->latest()->first();
                $invoicePeriod = Carbon::parse($latestInvoice->periode)->add($renewalPeriod);
                $nextBilledAt = Carbon::parse($latestInvoice->end_periode)->subDays($intervalDayInvoice);
            }
        }

        /*
        $customerPaket->forceFill([
            'start_date' => $customerPaketStartDate,
            'activation_date' => $newActivationDate ?? $customerPaket->activation_date,
            'expired_date' => $customerPaketExpiredDate,
            'next_billed_at' => $nextBilledAt
        ])->save();

        $invoicePeriod = Carbon::parse($nextBilledAt)->startOfMonth();
        if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
            // dd('buat invoice');
            (new BillingService())->generateInvoice($customerPaket);
        }*/
    }

    /*    public function paymentCustomerPaket(CustomerPaket $customerPaket)
    {
        $expiredDate = $this->deadlineService->calculateDeadline($customerPaket);
        $customerPaket->start_date = is_null($customerPaket->expired_date) ? now() : $customerPaket->expired_date;
        $customerPaket->expired_date = $expiredDate;
        $customerPaket->save();
    }
   */
    private function create_ppp_secret($mikrotik, $customerPaket, $comment, $disabled)
    {
        $pppSecret = (new MikrotikPppService())->createSecret($mikrotik, $customerPaket, $disabled, $comment);
        //dd($pppSecret);
        if ($pppSecret['success']) {
            $secretID = $pppSecret['secret_id'];
            (new CustomerPaketAction())->update_secret_id($customerPaket, $secretID);
            $customerPaket->activation();
            return [
                'success' => true,
                'message' => trans('customer.paket.alert.customer-paket-activation-detail', ['customer' => $customerPaket->user->full_name, 'paket' => $customerPaket->paket->name])
            ];
        }
        return $pppSecret;
    }

    /**
     * Summary of create_simpleque_ip_static
     * @param mixed $mikrotik
     * @param mixed $customerPaket
     * @param mixed $comment
     * @return array{message: array|string, success: bool|array{message: mixed, success: bool}|array{message: string, success: bool}}
     */

    private function create_simpleque_ip_static($mikrotik, $customerPaket, $comment)
    {
        $paketProfile = $customerPaket->paket->paket_profile;
        $ipStaticPaket = $customerPaket->customer_static_paket;
        $ip_static = $ipStaticPaket->ip_address;
        $mac_address = $ipStaticPaket->mac_address;
        $interface = $ipStaticPaket->interface;
        $simpleQueueName = $customerPaket->user->first_name . $ipStaticPaket->id;

        try {
            $createLimitasi = (new MikrotikIpStaticService())->createSimpleQueue($mikrotik, $simpleQueueName, $ip_static, $paketProfile, $comment);
            if (isset($createLimitasi['after']['message'])) {
                return [
                    'success' => false,
                    'message' => $createLimitasi['after']['message']
                ];
            } else {
                $simpleque_id = $createLimitasi['after']['ret'];
            }

            $createArp = (new MikrotikIpStaticService())->addIpToArp($mikrotik, $ip_static, $mac_address, $interface, $comment);

            if (isset($createArp['after']['message'])) {
                (new MikrotikIpStaticService())->deleteIpFromSimpleQueue($mikrotik, $simpleque_id);
                return [
                    'success' => false,
                    'message' => $createArp['after']['message']
                ];
            } else {
                $arp_id = $createArp['after']['ret'];
                (new CustomerPaketAction())->update_ip_static_paket_id($ipStaticPaket, $simpleque_id, $arp_id, $simpleQueueName);
                $customerPaket->activation();
                return [
                    'success' => true,
                    'message' => trans('customer.paket.alert.customer-paket-activation-detail', ['customer' => $customerPaket->user->full_name, 'paket' => $customerPaket->paket->name])
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Summary of update_ppp_paket
     * @param \App\Models\Customers\CustomerPppPaket $customerPppPaket
     * @param array $input
     * @return array{message: string, success: bool|array{success: bool}}
     */
    public function update_ppp_paket(CustomerPppPaket $customerPppPaket, array $input)
    {
        try {
            if (!is_null($customerPppPaket->customer_paket->activation_date)) {
                // dd($customerPppPaket->customer_paket->activation_date);
                $pppTypeName = PppType::whereId($input['selectedPppService'] ?? $customerPppPaket->ppp_type_id)->first()->name;
                $response = (new MikrotikPppService())->updateUsernameAndPasswordSecret(
                    $customerPppPaket->customer_paket->paket->mikrotik,
                    $customerPppPaket,
                    $input['username'],
                    $input['password_ppp'],
                    $pppTypeName
                );
            }
            if ($response['success']) {
                (new CustomerPaketAction())->updatePppPaket($customerPppPaket, $input);
            }
            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Summary of update_ip_static
     * @param \App\Models\Customers\CustomerStaticPaket $customerStaticPaket
     * @param array $input
     * @return array{message: string, success: bool|array{success: bool}}
     */
    public function update_ip_static(CustomerStaticPaket $customerStaticPaket, array $input)
    {
        try {
            $paket = $customerStaticPaket->customer_paket->paket;
            $mikrotik = $paket->mikrotik;
            if (!is_null($customerStaticPaket->customer_paket->activation_date)) {
                (new MikrotikIpStaticService())->updateSimpleQueue($mikrotik, $customerStaticPaket->simpleque_id, $paket->paket_profile, $customerStaticPaket->ip_address);
            }
            (new CustomerPaketAction())->updateStaticPaket($customerStaticPaket, $input);
            return  [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Summary of update_customer_paket
     * @param \App\Models\Customers\CustomerPaket $customerPaket
     * @param \App\Models\Pakets\Paket $paket
     * @return array{message: string, success: bool|array{success: bool}}
     */
    public function update_customer_paket(CustomerPaket $customerPaket, Paket $paket)
    {
        try {
            if (!is_null($customerPaket->activation_date)) {
                if ($customerPaket->internet_service->value == 'ppp') {
                    $response = (new MikrotikPppService())->updateProfileSecret(
                        $customerPaket->paket->mikrotik,
                        $customerPaket->customer_ppp_paket,
                        $paket->paket_profile->profile_name
                    );
                    if (!$response['success']) return $response;
                } else if ($customerPaket->internet_service->value == 'ip_static') {
                    $response = (new MikrotikIpStaticService())->updateSimpleQueue(
                        $customerPaket->paket->mikrotik,
                        $customerPaket->customer_static_paket->simpleque_id,
                        $paket->paket_profile->profile_name,
                        $customerPaket->customer_static_paket->ip_address
                    );
                    if (!$response['success']) return $response;
                }
            }
            (new CustomerPaketAction())->updatePaketCustomerPaket($customerPaket, $paket->id);
            return  [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Summary of deleteCustomerPaket
     * @param \App\Models\Customers\CustomerPaket $customerPaket
     * @return void
     */
    public function deleteCustomerPaket(CustomerPaket $customerPaket, $deleteOnMikrotik = false)
    {
        if ($deleteOnMikrotik) {
            $this->delete_user_on_mikrotik($customerPaket);
        } else {
            $this->disableCustomerPaketOnMikrotik($customerPaket, 'true');
        }

        (new CustomerPaketAction())->delete_paket($customerPaket);
    }

    /**
     * Summary of delete_user_on_mikrotik
     * @param mixed $customerPaket
     *
     */
    public function delete_user_on_mikrotik($customerPaket)
    {
        $mikrotik = $customerPaket->paket->mikrotik;

        if ($customerPaket->isPpp()) {
            $customerPppPaket = $customerPaket->customer_ppp_paket()->withTrashed()->first();
            return (new MikrotikPppService())->deleteSecret($mikrotik, $customerPppPaket);
        } else if ($customerPaket->isIpStatic()) {
            $customerStaticPaket = $customerPaket->customer_static_paket()->withTrashed()->first();
            $arp =  (new MikrotikIpStaticService())->deleteIpStaticPaket($mikrotik, $customerStaticPaket->ip_address);
            if (isset($arp['after']['message'])) return ['success' => false, 'message' => $arp['after']['message']];
            return ['success' => true];
        }
    }

    public function updateStatusCustomerPaket(CustomerPaket $customerPaket, $status)
    {
        $disabled = $status == 'active' ? 'false' : 'true';
        $cancelled = $status == 'cancelled' ? true : false;
        //dd($status);
        // $suspended = $status == 'suspended' ? true : false;
        if ($cancelled) {
            $deleteUserMikrotik = $this->delete_user_on_mikrotik($customerPaket);
            if (!$deleteUserMikrotik) return $deleteUserMikrotik;
            $customerPaket->forceFill([
                'start_date' => null,
                'expired_date' => null,
                'next_billed_at' => null,
                'activation_date' => null,
                'status' => 'cancelled'
            ])->save();
            // $customerPaket->invoices()->delete();
        } else {
            $disableOnMikrotik = $this->disableCustomerPaketOnMikrotik($customerPaket, $disabled);
            if (!$disableOnMikrotik['success']) return $disableOnMikrotik;
        }
        if ($status == 'active' && $customerPaket->status == 'expired') {
            // dd('expired');
            $restoreCustomerPaketStatus = $this->restore_customer_paket($customerPaket);
            // dd($restoreCustomerPaketStatus);
            if (!$restoreCustomerPaketStatus['success']) return $restoreCustomerPaketStatus;
        }

        (new CustomerPaketAction())->update_status_customer_paket($customerPaket, $status);
        return [
            'success' => true,
            'status' => $customerPaket->status
            // 'title' => $customerPaket->status == 'active' ? trans('customer.paket.alert.enable') : trans('customer.paket.alert.disable'),
            // 'message' => $customerPaket->status == 'active' ? trans('customer.paket.alert.customer-paket-enable-detail', ['customer' => $customerPaket->user->full_name, 'paket' => $customerPaket->paket->name]) : trans('customer.paket.alert.customer-paket-disable-detail', ['customer' => $customerPaket->user->full_name, 'paket' => $customerPaket->paket->name])
        ];
    }

    public function disableCustomerPaketOnMikrotik(CustomerPaket $customerPaket, $disabled)
    {
        $mikrotik = $customerPaket->paket()->withTrashed()->first()->mikrotik;

        try {
            if ($customerPaket->isPpp()) {
                $customerPppPaket = $customerPaket->customer_ppp_paket()->withTrashed()->first();
                //PPP Service
                $pppSecret = (new MikrotikPppService())->disableSecret($mikrotik, $customerPppPaket, $disabled);
                if (!$pppSecret['success']) return $pppSecret;
            } else if ($customerPaket->isIpStatic()) {
                //IP Static Service
                $customerStaticPaket = $customerPaket->customer_static_paket()->withTrashed()->first();
                $arp =  (new MikrotikIpStaticService())->disableIpFromArp($mikrotik, $customerStaticPaket->arp_id, $disabled);
                if (isset($arp['after']['message'])) return ['success' => false, 'message' => $arp['after']['message']];
            }
            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }



    public function disableWaNotificationInstallationAddress(CustomerPaket $customerPaket)
    {
        try {
            (new CustomerPaketAddressAction())->disableWaNotificationInstallationAddress($customerPaket->customer_installation_address);
            return [
                'success' => true,
                'message' => $customerPaket->customer_installation_address->wa_notification ? trans('customer.paket.alert.wa-notification-installation-address-enable') : trans('customer.paket.alert.wa-notification-installation-address-disable')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Summary of restore_customer_paket
     * @param \App\Models\Customers\CustomerPaket $customerPaket
     * @return array{message: mixed, success: array{result: mixed, success: bool|array{success: bool}|bool}|array{message: mixed, success: bool}|array{message: string, success: bool}|array{status: array, success: bool}|array{status: mixed, success: bool}}
     */
    public function restore_customer_paket(CustomerPaket $customerPaket)
    {
        try {
            if ($customerPaket->isPpp()) {
                $updateMikrotikStatus = (new MikrotikPppService())->updateProfileSecret(
                    $customerPaket->paket->mikrotik,
                    $customerPaket->customer_ppp_paket,
                    $customerPaket->paket->paket_profile->profile_name
                );
                if (!$updateMikrotikStatus['success']) return $updateMikrotikStatus;
            } else if ($customerPaket->isIpStatic()) {
                $updateMikrotikStatus = (new MikrotikIpStaticService())->disableIpFromArp(
                    $customerPaket->paket->mikrotik,
                    $customerPaket->customer_static_paket->arp_id,
                    'false'
                );
                if (isset($updateMikrotikStatus['after']['message'])) return ['success' => false, 'message' => $updateMikrotikStatus['after']['message']];
            }


            return  [
                'success' => true,
                'status' => $updateMikrotikStatus
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function commentMikrotik($expiredDate)
    {
        $websystem = Websystem::first();
        $commentUnpayment = $websystem->comment_unpayment;
        $comment = $commentUnpayment . '_' . Carbon::parse($expiredDate)->format('d_m_Y');
        return $comment;
    }

    public function isolir_secret_ppp_on_mikrotik($customerPaket)
    {
        $mikrotik = $customerPaket->paket->mikrotik;
        $autoIsolir = AutoIsolir::where('mikrotik_id', $mikrotik->id)->first();

        $profileIsolir = $autoIsolir->profile_id;
        try {
            $customerPppPaket = $customerPaket->customer_ppp_paket;
            $this->mikrotikPppService->updateProfileSecret($mikrotik, $customerPppPaket, $profileIsolir);
            return [
                'success' => true
            ];
        } catch (Exception $e) {
            // Log error untuk debugging
            \Log::error('Error isolir secret PPP on mikrotik: ' . $e->getMessage(), [
                'customer_paket_id' => $customerPaket->id ?? 'unknown',
                'mikrotik_id' => $mikrotik->id ?? 'unknown',
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat isolir secret PPP: ' . $e->getMessage()
            ];
        }
        //}
    }

    public function isolir_paket_static_on_mikrotik($customerPaket)
    {
        $mikrotik = $customerPaket->paket->mikrotik;
        $autoIsolir = AutoIsolir::where('mikrotik_id', $mikrotik->id)->first();
        if (!$autoIsolir->disabled) {
            return [
                'success' => false
            ];
        }
    }

    public function restore_deleted_customer_paket(CustomerPaket $customerPaket, $restoreOnMikrotik = false)
    {
        $customerPaket->restore();
        if ($customerPaket->isPpp()) {
            $customerPaket->customer_ppp_pakets()->withTrashed()->restore();
        } else if ($customerPaket->isIpStatic()) {
            $customerPaket->customer_static_pakets()->withTrashed()->restore();
        }

        $customerPaket->customer_paket_addresses()->withTrashed()->restore();
        $customerPaket->invoices()->withTrashed()->restore();

        //Enable secret on mikrotik
        $this->disableCustomerPaketOnMikrotik($customerPaket, 'false');
        $this->generalLogServices->admin_action($this->generalLogServices::RESTORE_CUSTOMER_PAKET, "Restore customer paket with invoices " . ' Server ' . $customerPaket->paket()->withTrashed()->first()->mikrotik()->withTrashed()->first()->name . ' Customer ' . $customerPaket->user()->withTrashed()->first()->full_name);

        if ($restoreOnMikrotik) {
            $this->restoreCustomerPaketOnMikrotik($customerPaket, $restoreOnMikrotik);
        }
    }

    public function restoreCustomerPaketOnMikrotik($customerPaket, $restoreOnMikrotik = false)
    {
        $mikrotik = $customerPaket->mikrotik;
        if ($customerPaket->isPpp()) {
            /*PPP Paket*/
            $customerPppPaket = $customerPaket->customer_ppp_paket()->withTrashed()->first();
            $isExist = $this->mikrotikPppService->userSecretIsExist($mikrotik, $customerPppPaket->username);

            if ($restoreOnMikrotik) { //Restore User Secret On Mikrotik
                if (!$isExist) { // If not exist, create new user secret
                    $this->createCustomerPaket($customerPaket);
                } else { // If exist, enable it
                    $this->disableCustomerPaketOnMikrotik($customerPaket, 'false');
                }
            } else {
                $this->delete_user_on_mikrotik($customerPaket);
                $customerPaket->forceFill([
                    'status' => 'cancelled'
                ])->save();
            }
        } else if ($customerPaket->isIpStatic()) {
            //
        }
    }

    public function syncronize_next_billed_at(CustomerPaket $customerPaket)
    {
        $intervalDayInvoice = Websystem::first()->different_day_create_billing;
        $newestInvoice = $customerPaket->invoices()->latest()->first();
        $customerPaket->forceFill([
            'next_billed_at' => Carbon::parse($newestInvoice->end_periode)->subDays($intervalDayInvoice)
        ])->save();
    }
}
