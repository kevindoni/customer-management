<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal\NotificationMessage;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Jobs\SendWhatsappJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class SendMessageAllCustomerPaket extends Component
{
    public $sendMessageAllCustomerPaketModal = false;
    public $input = [];

     #[On('show-send-message-all-customer-paket-modal')]
    public function showSendMessageAllCustomerPaketModal()
    {
        $this->sendMessageAllCustomerPaketModal = true;
    }

    private function delayProcess()
    {
        if (env('QUEUE_CONNECTION') === 'database'){
            return DB::table('jobs')->count()*10;
        } else {
            return 0;
        }

    }

    public function sendMessageAllCustomerPaket()
    {
        Validator::make(
            $this->input,
            [
                'message' => ['required', 'min:10'],
            ])->validate();

        $usersHavePaket = User::whereHas('customer_paket_addresses', function ($builder) {
            $builder->whereAddressType('installation-address')->whereWaNotification(true)->whereNotNull('phone');
        })
        ->with('customer_paket_addresses', function ($builder) {
            $builder->whereAddressType('installation-address')->whereWaNotification(true)->whereNotNull('phone');
        })
        ->get();

        $i = 0;
        foreach($usersHavePaket as $userHavePaket){
            $installationAddress = $userHavePaket->customer_paket_addresses->where('address_type', 'installation-address')->first();
           if ($installationAddress){
                $phoneNumber = $installationAddress->phone;
                $message = $this->input['message'];
                $i++;
                 dispatch(new SendWhatsappJob(
                    $phoneNumber,
                    $message
                ))->delay($this->delayProcess());
           }

        }

        $this->notification(trans('Success'), trans('Send message to '.$i.' customers.'), 'success');

        $this->sendMessageAllCustomerPaketModal = false;
    }

    private function notification($title, $msg, $status)
    {
        LivewireAlert::title($title)
            ->text($msg)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.notification-message.send-message-all-customer-paket');
    }
}
