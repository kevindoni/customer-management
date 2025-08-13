<?php

namespace App\Services\WhatsappGateway;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\API\WhatsappGateway;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;




class WhatsappService
{
    public function getAccountBank()
    {
        $account_banks_message = 'Outlet terdekat kami.';
        $account_banks = Bank::where('disabled', false)->get();

        if (count($account_banks)) {
            $i = 0;
            $account_banks_message = '';
            foreach ($account_banks as $account_bank) {
                $i++;
                $account_banks_message .= $i . '. ' . $account_bank->bank_name . ' - ' . $account_bank->account_number . ' - ' . $account_bank->account_name . '%0a';
            }
        }
        return $account_banks_message;
    }
    /*
  public function sendMessageNotification($receive, $message)
    {
        $whatsappGateway = WhatsappGatewayGeneral::first();
        if (!$whatsappGateway->disabled) {
            $data = [
                'sender' => $whatsappGateway->whatsapp_number_notification,
                'number' => $receive,
                'message' => $message
            ];
            Log::info('sendMessage');
            dispatch(new SendWhatsappJob($data))->onQueue('default');
        }
    }
*/

    public function sendNotification($receive, $message)
    {

        $whatsappGateway = WhatsappGatewayGeneral::first();
        if (!$whatsappGateway->disabled) {
            $data = [
                'sender' => $whatsappGateway->whatsapp_number_notification,
                'number' => $receive,
                'message' => $message
            ];
            //$response = (new GatewayApiService)->sendNotificationMessage(WhatsappGateway::SEND_MESSAGE, $data);
            $response = (new GatewayApiService)->sendMessage(WhatsappGateway::SEND_MESSAGE, $data);
        }
    }

    /*
    public function sendAdminNotification($message)
    {
        $whatsappGateway = WhatsappGatewayGeneral::first();
        $admins = User::whereHas('user_admin')->get();
        foreach ($admins as $admin) {
            if ($admin->user_address->wa_notification && $admin->user_address->phone != null) {
                $data = [
                    'sender' => $whatsappGateway->whatsapp_number_notification,
                    'number' => $admin->user_address->phone,
                    'message' => $message
                ];
                $response = (new GatewayApiService)->sendMessage(WhatsappGateway::SEND_MESSAGE, $data);
            }
        }
    }
        */
}
