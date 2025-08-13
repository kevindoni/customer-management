<?php

namespace App\Livewire\Admin\Settings\General;

use Livewire\Component;
use App\Models\Websystem;
use App\Http\Requests\Websystem\ConfigGeneralRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class UpdateGeneralForm extends Component
{
    public Websystem $websystem;
    public $input = [];

    public function mount()
    {
        $this->websystem = Websystem::first();

        $this->input = array_merge([
            'app_url' => env('APP_URL'),
            'diff_day' => $this->websystem->different_day_create_billing
        ], $this->websystem->withoutRelations()->toArray());
    }


    public function update_general(ConfigGeneralRequest $configGeneralRequest)
    {
        $configGeneralRequest->validate($this->input);

        $this->websystem->forceFill([
            'title' => $this->input['title'] ?? 'Customer Management 2',
            'email' => $this->input['email'],
            'different_day_create_billing' => $this->input['diff_day'],
            'address' => $this->input['address'],
            'city' => $this->input['city'],
            'postal_code' => $this->input['postal_code'],
            'phone' => $this->input['phone'],
            'tax_rate' => $this->input['tax_rate'] ?? 0,
            // 'app_url' => $this->state['app_url'],
        ])->save();

        //$this->input['title'] = null;
        setEnv('APP_URL', $this->input['app_url'] ?? 'http://localhost');
        setEnv('APP_NAME', $this->input['title'] ? "'".$this->input['title']."'" : "'Customer Management 2'");

        LivewireAlert::title(trans('websystem.alert.updated'))
            ->text(trans('websystem.alert.updated-message-successfully'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
    }

    public function render()
    {
        return view('livewire.admin.settings.general.update-general-form');
    }
}
