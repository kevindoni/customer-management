<?php

namespace App\Livewire\Admin\WhatsappGateway\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\GatewayApiService;
//use App\Services\WhatsappGateway\SubscriptionService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\WhatsappGateway\SubscriptionRequest;


class EditSubscription extends Component
{
    public $editSubscriptionModal = false;
    public $subscription;
    public $subscriptionPlans = [];
    public $paymentMethods = [];
    public $products = [];
    public $input = [];
    public $order;
    public $user;
    public $inctructions;
    public $descriptionProduct;
    public $currentStep = 0;



    #[On('show-add-subscription-modal')]
    public function addSubscriptionModal()
    {
        $this->editSubscriptionModal = true;
        $addSubscription = (new GatewayApiService())->createRequest(WhatsappGateway::SUBSCRIPTION);
        $result = $addSubscription['result'];
        $statusCode = $addSubscription['status_code'];
      // dd($result);
        
        if ($result['success']) {
            $this->currentStep = 1;
            $this->subscriptionPlans = $result['data']['subscription_plans'];
            $this->products = $result['data']['products'];
        } else {
             $this->notification('Error', $result['message'], 'error');
        }

    }



    #[On('show-edit-subscription-modal')]
    public function editSubscriptionModal($subscription = null)
    {
        $this->editSubscriptionModal = true;
        $editSubscription = (new GatewayApiService())->showRequest(WhatsappGateway::SUBSCRIPTION, $subscription);
        $result = $editSubscription['result'];
        $statusCode = $editSubscription['status_code'];
       // dd($result);
        if ($statusCode != 404){
            if ($result['success']) {
                $this->currentStep = 1;
                $this->subscription = $result['data']['subscription'];
                $this->subscriptionPlans = $result['data']['subscription_plans'];
                $this->products = $result['data']['products'];
                if ($this->subscription) {
                    $this->input['product'] = $this->subscription['product'];
                    $this->input['renewal_period'] = $this->subscription['renewal_period'];
                }
            } else {
                if ($statusCode == 402) {
                    $this->currentStep = 2;
                    $this->input['paymentCode'] = $result['data']['order']['pay_code'];
                    $this->order = $result['data']['order'];
                }
            }
        } else {
            $this->notification('Error', 'Your subscription not available.', 'error');
        }
    }

    public function updatedInputProduct($value)
    {
        $this->input['renewal_period'] = '';
        $this->input['payment_method'] = '';
        $this->paymentMethods = [];
        if ($value) {
            $subscriptionPlans =  Cache::remember('subscription-plan', now()->addMinutes(15), function () {
                return (new GatewayApiService())->getRequest(WhatsappGateway::SUBSCRIPTION_PLAN);
            });
            $result = $subscriptionPlans['result'];
            if ($result['success']) {
                $this->subscriptionPlans = $result['data']['subscription_plans'];
            } else {
                $this->notification('Error', $result['message'], 'error');
            }
        }
        $product = collect($this->products)->where('id', $value)->first();
        $this->descriptionProduct = $product['description'] ?? 'Please select product!';
    }

    public function updatedInputRenewalPeriod($value)
    {
        $this->input['payment_method'] = '';
        if ($value) {
            $paymentMethods =  Cache::remember('payment-methods', now()->addMinutes(15), function () {
                return (new GatewayApiService())->getRequest(WhatsappGateway::PAYMENT_METHODE);
            });
            $result = $paymentMethods['result'];
            if ($result['success']) {
                $this->paymentMethods = $result['data']['payment_methods'];
            } else {
                $this->notification('Error', $result['message'], 'error');
            }
        }
    }

    public function addSubscription(SubscriptionRequest $subscriptionRequest)
    {
        $subscriptionRequest->validate($this->input);
        $response = (new GatewayApiService())->addRequest(WhatsappGateway::SUBSCRIPTION,  $this->input);
        if ($response['result']['success']) {
            $this->currentStep = 2;
            $this->input['paymentCode'] = $response['result']['data']['order']['pay_code'];
            $this->order = $response['result']['data']['order'];
        } else {
            switch ($response['result']['status_code']) {
                case 400:
                    $msg = implode(' ', array_map(function ($entry) {
                        return ($entry[key($entry)]);
                    }, $response['result']['data']));
                    $this->notification($response['result']['message'], $msg, 'error');
                    break;
                default:
                    $this->notification('Error', $response['result']['message'], 'error');
            }
        }
    }

    public function updateSubscription(SubscriptionRequest $subscriptionRequest)
    {
        $subscriptionRequest->validate($this->input);
        $response = (new GatewayApiService())->updateRequest(WhatsappGateway::SUBSCRIPTION, env('API_USERNAME'), $this->input);

        if ($response['status_code'] == 204) {
            $this->notification('success', 'Tidak ada perunahan layanan.', 'success');
            $this->closeModal();
        } else {
            if ($response['result']['success']) {
                $this->notification('success', 'Update subscription successfully.', 'success');
                $this->currentStep = 2;
                $this->input['paymentCode'] = $response['result']['data']['order']['pay_code'];
                $this->order = $response['result']['data']['order'];
            } else {
                switch ($response['status_code']) {
                    case 400:
                        $msg = implode(' ', array_map(function ($entry) {
                            return ($entry[key($entry)]);
                        }, $response['result']['data']));
                        $this->notification($response['result']['message'], $msg, 'error');
                        break;
                    default:
                        $this->notification('Error', $response['result']['message'], 'error');
                }
            }
        }
    }

    public function instructions()
    {
        //dd($this->order);
        $this->inctructions = json_decode($this->order['instructions'], true);
        $this->currentStep = 3;
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }
    public function closeModal()
    {
        $this->editSubscriptionModal = false;
    }

    public function notification($title, $content, $status)
    {

        LivewireAlert::title($title)
            ->text($content)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }
    public function render()
    {
        return view('livewire.admin.whatsapp-gateway.modal.edit-subscription');
    }
}
