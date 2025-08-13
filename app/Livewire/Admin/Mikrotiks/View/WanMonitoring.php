<?php

namespace App\Livewire\Admin\Mikrotiks\View;

use Livewire\Component;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\ScriptService;

class WanMonitoring extends Component
{
    public Mikrotik $mikrotik;
    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }


    public function render()
    {
        return view('livewire.admin.mikrotiks.view.wan-monitoring');
    }
}
