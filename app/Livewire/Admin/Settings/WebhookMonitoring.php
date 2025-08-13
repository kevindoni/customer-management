<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Illuminate\Support\Str;

class WebhookMonitoring extends Component
{
    public $input = [];
    public function mount()
    {
        $this->input = array_merge([
            'apikey' => env('API_CLIENT_MIKROTIK'),
            'header_secret' => hash('sha256', env('API_CLIENT_MIKROTIK')),
        ]);
    }
    public function generateSigningSecret()
    {
        $this->input['apikey'] = Str::random(30);
        setEnv('API_CLIENT_MIKROTIK',  $this->input['apikey']);

        $this->input['header_secret'] = hash('sha256', env('API_CLIENT_MIKROTIK'));
    }
    public function render()
    {

        return view('livewire.admin.settings.webhook-monitoring');
    }
}
