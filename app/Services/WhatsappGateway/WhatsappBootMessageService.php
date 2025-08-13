<?php

namespace App\Services\WhatsappGateway;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\API\WhatsappGateway;
use App\Models\WhatsappGateway\WhatsappBootMessage;

use App\Services\WhatsappGateway\GatewayApiService;
use App\Livewire\Actions\Customers\UserBootMessageAction;
use App\Services\WhatsappGateway\WhatsappActionMessageService;



//use Illuminate\Support\Facades\Log;

class WhatsappBootMessageService
{
    private function getAddress($customer_number)
    {
        return UserAddress::wherePhone($customer_number)->first();
    }

    private function getMenuMessage($waBootMessage)
    {
        $menuMessage = "";
        $menus = WhatsappBootMessage::where('whatsapp_boot_message_id', $waBootMessage->id)
            ->orderBy('command_number')
            ->where('disabled', false)
            ->where('hidden_message', false)
            ->get();
        foreach ($menus as $menu) {
            $menuMessage .= trans('whatsapp-gateway.wa-message.menu', ['no' => $menu->command_number, 'menu' => $menu->name]);
        }
        $replace['%menu%'] = $menuMessage;
        return str_replace(array_keys($replace), $replace, $waBootMessage->message);
    }

    private function getCustomerName($user, $message)
    {
        if ($user->user_customer->gender == 'male') {
            $gelar = 'Bp.';
        } else if ($user->user_customer->gender == 'female') {
            $gelar = 'Ibu';
        } else {
            $gelar = 'Bp/Ibu';
        }

        $replace['%name%'] = $gelar . ' ' . $user->full_name;
        return str_replace(array_keys($replace), $replace, $message);
    }

    private function getCustomerNameAndMenuMessage(User $user, $waBootMessage)
    {
        $waBootMessage = $this->getMenuMessage($waBootMessage);
        return $this->getCustomerName($user, $waBootMessage);
    }


    public function replyMessage($customer_number, $receipt_number, $incoming_message)
    {
        $len_message = strlen($incoming_message);

        if ($len_message > 0) {
            $userAddress = $this->getAddress($customer_number);
            if ($userAddress) {
                $user = $userAddress->user;
                $customer = $user->user_customer;
                if ($customer) {
                    $userBootMessage = $user->user_boot_message;
                    if (is_null($userBootMessage)) {
                        //first time message user
                        $waBootMessage = WhatsappBootMessage::whereNull('whatsapp_boot_message_id')->first();
                        // return dd($waMessage);
                        $message = $this->getCustomerNameAndMenuMessage($user, $waBootMessage);
                        (new UserBootMessageAction())->add($user->id, $waBootMessage->id);
                    } else {
                        $waBootMessage = $userBootMessage->whatsapp_boot_message;

                        if ($incoming_message == 0) {
                            $waBootMessage = WhatsappBootMessage::find($waBootMessage->whatsapp_boot_message_id ?? 0);
                            $message = $this->getCustomerNameAndMenuMessage($user, $waBootMessage);
                            (new UserBootMessageAction())->update($userBootMessage, $user->id, $waBootMessage->id);
                        } else {
                            $waBootMessage = WhatsappBootMessage::where('whatsapp_boot_message_id', $userBootMessage->whatsapp_boot_message_id)->where('command_number', $incoming_message)->first();
                            // $getParents = WaMessage::where('whatsapp_boot_message_id', $waMessage->id)->get();
                            if (is_null($waBootMessage) && is_null($userBootMessage->whatsapp_boot_message->action_message)) {
                                $waBootMessage = $user->user_boot_message->whatsapp_boot_message;
                                $lastMessage = $this->getCustomerNameAndMenuMessage($user, $waBootMessage);
                                $replace = [
                                    '%last_message%' =>  $lastMessage
                                ];
                                $message = trans('whatsapp-gateway.wa-message.false-user-reply-message');
                                $message = str_replace(array_keys($replace), $replace, $message);
                            } else {

                                if (is_null($userBootMessage->whatsapp_boot_message->action_message)) {

                                    if ($waBootMessage->end_message) {
                                        $actionMessage = $waBootMessage->action_message;
                                        $message = (new WhatsappActionMessageService())->$actionMessage($user, $waBootMessage->message);
                                        (new UserBootMessageAction())->delete($userBootMessage);
                                    } else {
                                        (new UserBootMessageAction())->update($userBootMessage, $user->id, $waBootMessage->id);
                                        $message = $this->getCustomerNameAndMenuMessage($user, $waBootMessage);
                                    }
                                } else {
                                    $actionMessage = $userBootMessage->whatsapp_boot_message->action_message;
                                    $message = (new WhatsappActionMessageService())->$actionMessage($user, $incoming_message,);
                                    (new UserBootMessageAction())->delete($userBootMessage);
                                }
                            }
                        }
                    }
                    $this->sendMessage([
                        'sender' => $receipt_number,
                        'number' => $customer_number,
                        'message' => $message
                    ]);
                }
            }
        }
    }


    private function sendMessage($data)
    {
        $response = (new GatewayApiService)->sendMessage(WhatsappGateway::SEND_MESSAGE, $data);
    }
}
