<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\Mikrotiks\ScriptService;
use App\Models\Servers\MikrotikMonitoring;

class WanMonitoring extends Component
{
    public $perPage = 10;


    public function add_script_to_mikrotik(MikrotikMonitoring $mikrotikMonitoring)
    {
        (new ScriptService())->add_and_remove_tmon_script_mikrotik($mikrotikMonitoring);

    }

    #[On('refresh-mikrotik-monitoring-list')]
    public function render()
    {
        $mikrotikMonitorings = MikrotikMonitoring::where('disabled', false)
            ->paginate($this->perPage);
        return view('livewire.admin.settings.wan-monitoring', compact('mikrotikMonitorings'));
    }
}
