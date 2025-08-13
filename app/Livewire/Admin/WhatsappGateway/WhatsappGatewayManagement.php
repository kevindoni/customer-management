<?php

namespace App\Livewire\Admin\WhatsappGateway;

use Livewire\Component;
use App\Models\Websystem;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\DeviceService;
use App\Services\WhatsappGateway\GatewayApiService;
use App\Services\WhatsappGateway\SubscriptionService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;

class WhatsappGatewayManagement extends Component
{
    //use WhatsappGatewayTrait;
    public $input = [];
    public $user;
    public $enable = false;
    public $notifAdmin = false;


    public function initialize()
    {
        $company = Websystem::first();
        $this->input['email'] = $company->email;
        Validator::make($this->input, [
            'password' =>  ['required', 'string', Rules\Password::defaults()],
            'email' => ['required', 'string', 'email', 'lowercase', 'max:255'],
        ], [
            'email.required' => 'Please update your company email from General Setting page!'
        ])->validate();

        try {
            $response = (new GatewayApiService())->initializeRequest(WhatsappGateway::INIT, $this->input);
            $responseCode = $response['status_code'];
            $response = $response['result'];

            if ($response['success']) {
                $user = $response['data']['user'];
                $this->dispatch('login-success');
                setEnv('API_CLIENT_SECRET', $user['login_token'] ?? '');
                setEnv('API_CLIENT_MESSAGE', $user['wagateway_token'] ?? '');
                $username = $user['username'];
                setEnv('API_USERNAME', $username);
                $info = 'Syncronyze successfully with ' . $username;
                $this->notification('Success', $response['message'], 'success');
                $this->redirect('/managements/whatsapp-gateway', navigate: true);
            } else {
                switch ($responseCode) {
                    case 400:
                        $message = implode(' ', array_map(function ($entry) {
                            return ($entry[key($entry)]);
                        }, $response['data']));
                        $title = $response['message'];
                        break;

                    default:
                        $message = $response['message'];
                        $title = 'Error';
                        break;
                }

                $this->notification($title, $message, 'error');
            }
        } catch (\Exception $e) {
            $this->notification('Error', $e->getMessage(), 'error');
        }
    }

    private function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
            ->text($message)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function updatedEnable($value)
    {
        if ($value) {
            WhatsappGatewayGeneral::first()->enable();
        } else {
            WhatsappGatewayGeneral::first()->disable();
        }
    }

    public function updatedNotifAdmin($value)
    {
        if ($value) {
            WhatsappGatewayGeneral::first()->sendWaAdminEnable();
        } else {
            WhatsappGatewayGeneral::first()->sendWaAdminDisable();
        }
    }

    public function updatedInputWhatsappNumberBoot($value)
    {
        Validator::make($this->input, [
            'whatsapp_number_boot' => ['required'],
        ])->validate();
        WhatsappGatewayGeneral::first()->forceFill(['whatsapp_number_boot' => $value])->save();
        $this->dispatch('boot-number-updated');
    }

    public function updatedInputWhatsappNumberNotification($value)
    {
        Validator::make($this->input, [
            'whatsapp_number_notification' => ['required'],
        ])->validate();
        WhatsappGatewayGeneral::first()->forceFill(['whatsapp_number_notification' => $value])->save();
        $this->dispatch('notification-number-updated');
    }

    public function updatedInputRemainingDay($value)
    {
        Validator::make($this->input, [
            'remaining_day' => ['required', 'numeric', 'max:15']
        ])->validate();
        WhatsappGatewayGeneral::first()->forceFill(['remaining_day' => $value])->save();
        $this->dispatch('remaining-day-updated');
    }

    public function updatedInputScheduleTime($value)
    {
        Validator::make($this->input, [
            'schedule_time' => ['required'],
        ])->validate();

        setEnv('WA_REMINDER_TIME', $value);
        $this->dispatch('schedule-time-updated');
    }


    public function render(SubscriptionService $subscriptionService, DeviceService $deviceService)
    {
        $notifications = [];
        $devices = [];
        $subscription = [];

        try {
            $userSubscription = (new GatewayApiService())->getRequest(WhatsappGateway::SUBSCRIPTION);
            $userSubscription = $userSubscription['result'];
            $notifications = $userSubscription['data']['notifications'] ?? [];

            if ($userSubscription['success']) {
                $isLogin = true;
                $devices = collect($userSubscription['data']['devices'])->where('status', 'Connected');
                $this->user = $userSubscription['data']['user'];
                $subscription = $userSubscription['data']['subscription'] ?? [];
                $whatsappGateway = WhatsappGatewayGeneral::first();
                $this->notifAdmin = $whatsappGateway->send_wa_admin ? true : false;
                $this->enable =  $whatsappGateway->disabled ? false : true;
                $this->input = array_merge([
                    'schedule_time' => env('WA_REMINDER_TIME')
                ], $whatsappGateway->withoutRelations()->toArray());
            } else {
                $isLogin = false;
            }
        } catch (\Exception $e) {
            $isLogin = false;
            $this->notification('Error', $e->getMessage(), 'error');
        }

        return view('livewire.admin.whatsapp-gateway.whatsapp-gateway-management', compact('isLogin', 'devices', 'subscription', 'notifications'));
    }
}
