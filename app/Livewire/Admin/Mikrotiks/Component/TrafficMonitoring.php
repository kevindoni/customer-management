<?php

namespace App\Livewire\Admin\Mikrotiks\Component;


use Livewire\Component;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikService;
use App\Http\Resources\Mikrotik\InterfaceResource;
use App\Http\Resources\Mikrotik\TrafficInterfaceResource;


class TrafficMonitoring extends Component
{
    public Mikrotik $mikrotik;
    public $interfaces;
    public $selectedInterface = null;
    public $mikrotikOnline = false;

    public $firstRun = true;
    public $showDataLabels = false;
    public $traffic;

    // public $chartId;
    public $athlete;
    public $years;
    public $distances;
    public $total;


    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
        try {
            $this->interfaces = (new MikrotikService())->mikrotikEtherInterface($mikrotik);
            //dd($this->interfaces);
            $this->mikrotikOnline = true;
        } catch (\Exception $e) {
            $this->interfaces = null;
            $this->mikrotikOnline = false;
        }
    }



    //#[On('get-mikrotik-resource')]
    public function routerTraffic()
    {

        if (is_null($this->selectedInterface)) {
            $this->selectedInterface = $this->interfaces[0]['name'];
        }

        $traffic = (new MikrotikService())->monitoringTraffic($this->mikrotik,  $this->selectedInterface);
        $interfaces = InterfaceResource::collection($this->interfaces);
        $interfaces = $interfaces->where('name', $this->selectedInterface)->first();
        $traffic =  TrafficInterfaceResource::collection($traffic);
        $this->dispatch('traffic-from-mikrotik-' . $this->mikrotik->slug, ['traffic' => $traffic->toJson()]);
    }


    //Update selected Interface
    public function updatedSelectedInterface($interface)
    {
        $this->selectedInterface = $interface;
    }


    public function render()
    {
        return view('livewire.admin.mikrotiks.component.traffic-monitoring');
    }
}
