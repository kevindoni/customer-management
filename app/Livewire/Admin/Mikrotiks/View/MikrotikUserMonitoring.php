<?php

namespace App\Livewire\Admin\Mikrotiks\View;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikPppService;

class MikrotikUserMonitoring extends Component
{
    public Mikrotik $mikrotik;
    public $input = [];

    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
        $this->input = array_merge([
            'apikey' => env('API_CLIENT_MIKROTIK'),
            'header_secret' => hash('sha256', env('API_CLIENT_MIKROTIK')),
        ]);
    }

    public function activation()
    {
        $pakets = $this->mikrotik->pakets;
        foreach($pakets as $paket){
            (new MikrotikPppService())->updateScriptPppProfile($paket);
        }

    }
    public function render()
    {
        return view('livewire.admin.mikrotiks.view.mikrotik-user-monitoring');
    }
}
