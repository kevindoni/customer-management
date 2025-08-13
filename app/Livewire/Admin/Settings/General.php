<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikPppService;
use Livewire\Component;

class General extends Component
{
    public function render(MikrotikPppService $mikrotikPppService)
    {
        //$mikrotik = Mikrotik::first();
      //  dd($mikrotikPppService->getProfile($mikrotik, 'Profile 3 Mbps'));
        return view('livewire.admin.settings.general');
    }
}
