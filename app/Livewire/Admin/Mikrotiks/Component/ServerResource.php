<?php

namespace App\Livewire\Admin\Mikrotiks\Component;


use Livewire\Component;

use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikService;
use App\Http\Resources\Mikrotik\MikrotikTimeResource;
use App\Http\Resources\Mikrotik\MikrotikSystemResource;


class ServerResource extends Component
{
    public Mikrotik $mikrotik;
    public $mikrotikOnline = false;
    //public $resources;

    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
        try {
            (new MikrotikService())->getAllResources($this->mikrotik);
            $this->mikrotikOnline = true;
        } catch (\Exception $e) {

            $this->mikrotikOnline = false;
        }
    }

    public function routerResource()
    {
        $resources = (new MikrotikService())->getAllResources($this->mikrotik);
        $resources = MikrotikSystemResource::collection($resources);
       // dd($resources);
        $time = (new MikrotikService())->getTime($this->mikrotik);
        if (version_compare('7.10.0', $this->mikrotik->version, '<=')) {
            $time = MikrotikTimeResource::collection($time);
        } else {
            $time_format = array(
                [
                    'time' => str_replace('/', '-', $time[0]['date']),
                    'date' => $time[0]['time'],
                    'time-zone-autodetect' => $time[0]['time-zone-autodetect'],
                    'time-zone-name' => $time[0]['time-zone-name'],
                    'gmt-offset' => $time[0]['gmt-offset']
                ]
            );
            $time = MikrotikTimeResource::collection($time_format);
        }
        $this->dispatch('resource-from-mikrotik-' . $this->mikrotik->slug, ['resources' => $resources->toJson(), 'times' => $time->toJson()]);
    }

    public function render()
    {
        return view('livewire.admin.mikrotiks.component.server-resource');
    }
}
