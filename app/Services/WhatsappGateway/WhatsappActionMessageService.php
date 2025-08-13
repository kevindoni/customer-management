<?php

namespace App\Services\WhatsappGateway;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\WhatsappGateway\WhatsappService;


//use Illuminate\Support\Facades\Log;

class WhatsappActionMessageService
{
    //use CustomerPaket;
    //  use Billing;

    public function info_tagihan(User $user, $message)
    {
        // $websystem = $this->websystem();;
        $bill = "";
        $total_bill = 0;
        $i = 0;
        $account_banks = (new WhatsappService())->getAccountBank();
        $customer_pakets = $user->customer_pakets;
        $customer = $user->user_customer;
        $replace = [
            '%name%' => $user->full_name,
            '%customer_id%' => $customer->customer_id,
            '%address%' => $customer->address,
            '%account-banks%' => $account_banks,
        ];
        if (count($customer_pakets)) {
            foreach ($customer_pakets as $customer_paket) {
                $invoices = $customer_paket->invoices->where('status', '!=', 'paid');
                if (count($invoices)) {
                    foreach ($invoices as $invoice) {
                        $totalPaid = $invoice->payments->sum('amount');
                        $totalRefunded = $invoice->payments->sum('refunded_amount');
                        $netPaid = $totalPaid - $totalRefunded;
                        $totalBill = $invoice->amount - $netPaid;

                        $i++;
                        $startPeriode = Carbon::parse($customer_paket->activation_date)->format('d') . '-' . Carbon::parse($invoice->periode)->format('m-Y');
                        $bill .= trans('whatsapp-gateway.wa-message.info-detail-billing', ['no' => $i, 'start_periode' => Carbon::parse($startPeriode)->format('d F Y'), 'end_periode' => Carbon::parse($startPeriode)->add($customer_paket->getRenewalPeriod())->format('d F Y'), 'bill' => number_format($totalBill, 2)]);
                    }

                    $userCustomer = $customer_paket->user->user_customer;
                    $totalCustomerPaid = $userCustomer->payments->sum('amount');
                    $totalCustomerTax = $userCustomer->payments->sum('tax');
                    $totalCustomerDiscount = $userCustomer->payments->sum('discount');
                    $totalCustomerRefunded = $userCustomer->payments->sum('refunded_amount');
                    $netCustomerPaid = $totalCustomerPaid - $totalCustomerRefunded;
                    $totalCustomerBill = $invoices->sum('amount') + $totalCustomerTax - $totalCustomerDiscount - $netCustomerPaid;
                    //$total_bill = $invoices->sum('amount');


                    // $bill = 'Rp.' . number_format($payment->amount, 2).' '.
                    $replace['%paket-name%'] = $customer_paket->paket->name;
                    $replace['%bills%'] = $bill;
                    $replace['%total-bills%'] = ' Rp. ' . number_format($totalCustomerBill, 2);
                } else {
                    $message = 'Anda belum memiliki tagihan.';
                }
                return  str_replace(array_keys($replace), $replace, $message);
            }
        } else {
            $message = 'Anda belum memiliki paket.';
        }
        return  str_replace(array_keys($replace), $replace, $message);
    }

    public function info_paket(User $user, $message)
    {
        // $websystem = Websystem::first();
        $customer_pakets = $user->customer_pakets->where('status', 'active');
        $customer = $user->user_customer;
        $replace = [
            '%name%' => $customer->user->first_name,
            '%customer_id%' => $customer->customer_id,
            '%address%' => $customer->address,
        ];
        $paket = "";
        $i = 0;
        if (count($customer_pakets)) {
            foreach ($customer_pakets as $customer_paket) {
                $i++;
                $paket .= trans('whatsapp-gateway.wa-message.detail-paket', [
                    'no' => $i,
                    'paketname' => $customer_paket->paket->name,
                    'bill' => number_format($customer_paket->price, 2),
                    'deadline' => Carbon::parse($customer_paket->expired_date)->format('d')
                ]);
            }
            $replace['%pakets%'] = $paket;
        } else {
            $message = 'Anda belum memiliki paket.';
        }
        return  str_replace(array_keys($replace), $replace, $message);
    }

    public function change_ssid(User $user, $message)
    {
        $message = trans('whatsapp-gateway.wa-message.reply-user-action-message', ['action_name' => trans('whatsapp-gateway.wa-message.action.change-ssid'), 'value_user' => $message]);
        return $message;
    }

    public function change_password_wifi(User $user, $message)
    {
        $message = trans('whatsapp-gateway.wa-message.reply-user-action-message', ['action_name' => trans('whatsapp-gateway.wa-message.action.change-password-wifi'), 'value_user' => $message]);
        return $message;
    }

    //=================================Get information onu=====================================
    /*  public function info_wifi($userCustomer, $message)
    {
        if (!GenieAcs::first()->disabled) {
            $customer_pakets = $userCustomer->user->customer_pakets;
            $message = trans($message);

            if (count($customer_pakets)) {
                $info_wifi = "";
                $i = 0;
                foreach ($customer_pakets as $customer_paket) {
                    //$serial_number_onu = 'ZTEGCCB5A6DF';
                    $customerPaketOnu = $customer_paket->customer_paket_onu;
                    if ($customerPaketOnu) {
                        $serial_number_onu = $customer_paket->customer_paket_onu->onu->serial_number;
                    } else {
                        $serial_number_onu = false;
                    }

                    if ($serial_number_onu) {
                        $ssid_1 = "InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID";
                        $device = AcsServices::getDeviceBySerial($serial_number_onu);
                        if ($device) {
                            AcsServices::getParameterValues($ssid_1, $device[0]['_id']);
                            $get_devices = AcsServices::getDeviceById($device[0]['_id']);
                            $hosts = $get_devices[0]['InternetGatewayDevice']['LANDevice'][1]['Hosts']['Host'];
                            $host_name = '';
                            $x = 0;
                            if (count($hosts)) {
                                foreach ($hosts as $host) {
                                    if (!empty($host['HostName']['_value'])) {
                                        $x++;
                                        $host_name .= trans('wa-gateway.wa-message.device-connected', [
                                            'no' => $x,
                                            'host_name' => $host['HostName']['_value']
                                        ]);
                                    }
                                }
                            } else {
                                $host_name = trans('wa-gateway.wa-message.no-device-connected');
                            }


                            $i++;
                            $info_wifi .= trans('wa-gateway.wa-message.info-wifi', [
                                'no' => $i,
                                'ssid_id' => 'SSID1',
                                'ssid_name' => $get_devices[0]['InternetGatewayDevice']['LANDevice'][1]['WLANConfiguration'][1]['SSID']['_value'],
                                'installation_address' => $customer_paket->installation_address,
                                'device_connected' => $host_name
                            ]);
                            // $replace['%nama_wifi%'] = $get_devices[0]['VirtualParameters']['Ssid']['_value'];

                        } else {
                            $info_wifi .= 'Gagal';
                            // $message = 'Maaf sedang terjadi gangguan sistem';
                        }
                    } else {
                        $i++;
                        $info_wifi .=  trans('wa-gateway.wa-message.info-wifi-unconfigure', [
                            'no' => $i
                        ]);
                    }
                }
                $replace['%info_wifi%'] = $info_wifi;
            } else {
                // $message = $this->getDefaultMessage('no_paket');
                $message = 'Anda belum memiliki paket.';
            }
            return  str_replace(array_keys($replace), $replace, $message);
        }
    }
        */
}
