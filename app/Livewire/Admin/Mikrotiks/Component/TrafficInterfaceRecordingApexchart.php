<?php

namespace App\Livewire\Admin\Mikrotiks\Component;


use Illuminate\Support\Carbon;

use App\Models\Servers\Mikrotik;
use Illuminate\Support\Collection;
use App\Models\Servers\WanMonitoring;
use App\Charts\TrafficMonitoringApexchart;
use App\Services\Mikrotiks\MikrotikService;
use App\Support\Livewire\ApexchartComponent;
use App\Support\Livewire\ApexchartComponentData;

class TrafficInterfaceRecordingApexchart extends ApexchartComponent
{
    public Mikrotik $mikrotik;
    public $interfaces;
    public $selectedInterface = null;
    public $mikrotikOnline = false;
    public $startDate = '';
    public $endDate;
    public $limit = 50;

    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
        try {
            $this->interfaces = (new MikrotikService())->mikrotikEtherInterface($mikrotik);
            $this->mikrotikOnline = true;
           // dd($this->interfaces);
        } catch (\Exception $e) {
            $this->interfaces = null;
            $this->mikrotikOnline = false;
        }
    }


    /**
     * ==============================================================================================================================
     * Start chart
     * ===============================================================================================================================
     */

    protected function view(): string
    {
        return 'livewire.admin.mikrotiks.component.traffic-interface-recording-apexchart';
    }


    /**
     * @return string
     * 3
     */

    protected function chartClass(): string
    {
        return TrafficMonitoringApexchart::class;
    }

    /**
     * @return \App\Support\Livewire\ChartComponentData
     * 1
     */
    protected function chartData(): ApexchartComponentData
    {
        $traffic_monitorings = WanMonitoring::query()
            ->select(['id', 'created_at', 'tx_rate', 'rx_rate', 'ping_ms', 'mikrotik_id', 'interface_name'])
            // ->where('created_at', '>=', Carbon::now()->subSecond(30))
            // ->where('created_at', '>=', Carbon::now()->subMinutes(3))
            // ->where('created_at', '<=', Carbon::now())
            ->when($this->startDate || $this->endDate, function ($query) {
                $query->whereBetween('created_at', [Carbon::parse($this->startDate)->startOfDay(), Carbon::parse($this->endDate)->endOfDay()]);
            })
            //, function ($query) {
             //   $query->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
           // })
            // ->whereBetween('created_at', [Carbon::parse($this->startDate)->startOfDay(), Carbon::parse($this->endDate)->endOfDay()])
            ->where('mikrotik_id', $this->mikrotik->id)
           // ->when($this->startDate || $this->endDate, function ($query) {
           //     $query->orderBy('id', 'ASC');
           // })
            ->orderBy('id', 'DESC')
            ->limit($this->limit)
            // ->paginate($this->limit)
            ->get()
            ->reverse()
            ->values();



        $labels = $traffic_monitorings->map(function (WanMonitoring $traffic_monitoring) {
            return $traffic_monitoring->created_at->format('Y-m-d H:i:s');
        });

        $datasets = new Collection([
            $traffic_monitorings->map(function (WanMonitoring $traffic_monitoring) {
                return number_format($traffic_monitoring->rx_rate, 2, '.', '');
            }),
            $traffic_monitorings->map(function (WanMonitoring $traffic_monitoring) {
                return number_format($traffic_monitoring->tx_rate, 2, '.', '');
            })
        ]);
        $interfaces = $traffic_monitorings->map(function ($traffic_monitoring) {
            return $traffic_monitoring->interface_name;
        });
        // return dd($interface);
        return (new ApexchartComponentData($labels, $datasets, $interfaces, $this->mikrotik));
    }
}
