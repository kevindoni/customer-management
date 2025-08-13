<?php

namespace App\Livewire\Admin\Settings\PaymentGateway;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class MidtransForm extends Component
{
    public $input = [];
    public $enable;
    public $development;
    public $production;
   // private PaymentGateway $paymentGateway;

    public function mount()
    {
        $midtrans = PaymentGateway::whereValue('midtrans')->first();
        $this->input = array_merge([
            'merchant_code' => $midtrans->merchant_code,
        ], $midtrans->withoutRelations()->toArray());
        $this->production = $midtrans->mode == 'production' ? true : false;
        $this->development = $midtrans->mode == 'development' ? true : false;
    }

    public function updatedEnable($active)
    {
        if ($active) {
            PaymentGateway::whereValue('midtrans')->first()->setActive();
            PaymentGateway::whereValue('tripay')->first()->setNonActive();
        } else {
            PaymentGateway::whereValue('midtrans')->first()->setNonActive();
        }
        $this->dispatch('update-status-payment-gateway')->to(TripayForm::class);
    }

    public function updatedDevelopment($enable)
    {
        $midtrans = PaymentGateway::whereValue('midtrans')->first();
        if ($enable) {
            $midtrans->update([
                'mode' => 'development',
            ]);
        } else {
            $validator = Validator::make($this->input, [
                'merchant_code' => ['required'],
                 'production_api_key' => ['required'],
                 'production_secret_key' => ['required'],
            ]);
            if ($validator->fails()) {
                $this->development = true;
                $validator->validate();
            }
            $midtrans->update([
                'mode' => 'production',
            ]);
        }
        $this->development ? $this->production = false : $this->production = true;
    }

    public function updatedProduction($enable)
    {
        $midtrans = PaymentGateway::whereValue('midtrans')->first();
        if ($enable) {
            $validator = Validator::make($this->input, [
                'merchant_code' => ['required'],
                 'production_api_key' => ['required'],
                 'production_secret_key' => ['required'],
            ]);
            if ($validator->fails()) {
                $this->production = false;
                $validator->validate();
            }
            $midtrans->update([
                'mode' => 'production',
            ]);
            $this->development = false;
        } else {
            $midtrans->update([
                'mode' => 'development',
            ]);
        }
        $this->production ? $this->development = false : $this->development = true;
    }


    public function update_midtrans()
    {
        Validator::make($this->input, [
            'merchant_code' => ['required'],
            'development_api_key' => ['required'],
            'development_secret_key' => ['required'],
        ])->validate();

        PaymentGateway::whereValue('midtrans')->update([
            'merchant_code' => $this->input['merchant_code'],
            'production_api_key' => $this->input['production_api_key'],
            'production_secret_key' => $this->input['production_secret_key'],
            'development_api_key' => $this->input['development_api_key'],
            'development_secret_key' => $this->input['development_secret_key'],
        ]);

        LivewireAlert::title(trans('websystem.alert.updated'))
            ->text(trans('websystem.alert.midtrans-updated-successfully'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
    }

    #[On('update-status-payment-gateway')]
    public function render()
    {
        $midtrans = PaymentGateway::whereValue('midtrans')->first();
        $this->enable = $midtrans->is_active ? true : false;
        return view('livewire.admin.settings.payment-gateway.midtrans-form');
    }
}
