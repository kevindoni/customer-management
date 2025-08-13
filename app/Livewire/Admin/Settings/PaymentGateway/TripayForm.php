<?php

namespace App\Livewire\Admin\Settings\PaymentGateway;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class TripayForm extends Component
{
    public $input = [];
    public $enable;
    public $development;
    public $production;

    // private PaymentGateway $paymentGateway;

    public function mount()
    {
        $tripay = PaymentGateway::whereValue('tripay')->first();
        $this->input = array_merge([
            'merchant_code' => $tripay->merchant_code,
        ], $tripay->withoutRelations()->toArray());
        $this->production = $tripay->mode == 'production' ? true : false;
        $this->development = $tripay->mode == 'development' ? true : false;

    }

    public function updatedEnable($active)
    {
        if ($active) {
            PaymentGateway::whereValue('tripay')->first()->setActive();
            PaymentGateway::whereValue('midtrans')->first()->setNonActive();
        } else {
            PaymentGateway::whereValue('tripay')->first()->setNonActive();
        }
        $this->dispatch('update-status-payment-gateway')->to(MidtransForm::class);
    }

    public function updatedDevelopment($enable)
    {
        $tripay = PaymentGateway::whereValue('tripay')->first();
        if ($enable) {
            $tripay->update([
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
            $tripay->update([
                'mode' => 'production',
            ]);
        }
        $this->development ? $this->production = false : $this->production = true;
    }

    public function updatedProduction($enable)
    {
        $tripay = PaymentGateway::whereValue('tripay')->first();
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
            $tripay->update([
                'mode' => 'production',
            ]);
            $this->development = false;
        } else {
            $tripay->update([
                'mode' => 'development',
            ]);
        }
        $this->production ? $this->development = false : $this->development = true;
    }

    public function update_tripay()
    {
        Validator::make($this->input, [
            'merchant_code' => ['required'],
            // 'production_api_key' => ['required'],
            // 'production_secret_key' => ['required'],
            'development_api_key' => ['required'],
            'development_secret_key' => ['required'],
        ])->validate();

        PaymentGateway::whereValue('tripay')->update([
            'merchant_code' => $this->input['merchant_code'],
            'production_api_key' => $this->input['production_api_key'],
            'production_secret_key' => $this->input['production_secret_key'],
            'development_merchant_code' => $this->input['development_merchant_code'],
            'development_api_key' => $this->input['development_api_key'],
            'development_secret_key' => $this->input['development_secret_key'],
        ]);


        LivewireAlert::title(trans('websystem.alert.updated'))
            ->text(trans('websystem.alert.tripay-updated-successfully'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
    }

    #[On('update-status-payment-gateway')]
    public function render()
    {
        $tripay = PaymentGateway::whereValue('tripay')->first();
        $this->enable = $tripay->is_active ? true : false;
        return view('livewire.admin.settings.payment-gateway.tripay-form');
    }
}
