<?php

namespace App\Charts;

use Illuminate\Support\Facades\Log;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TrafficMonitoringApexchart extends LarapexChart
{
    protected $chart;
    protected $data;

    public function __construct()
    {
        $this->chart = new LarapexChart();
    }

    public function build($data)
    {
        Log::info($data->labels()->first());
        // return dd($data->mikrotik()->name);
        $networkFormatter = "function(value) {
            const sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
            if (value === 0) return '0 bps';
            const i = parseInt(Math.floor(Math.log(value) / Math.log(1024)));
            return parseFloat((value / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[
                i];
        },";
        return $this->chart->areaChart()
            ->setTitle('Server: ' . $data->mikrotik()->name.' - WAN Monitoring Past 24hours')
            ->setSubtitle($data->labels()->first() .' - '. $data->labels()->last())
            ->setFormatter('formatter:' . $networkFormatter)
            ->setStroke(1, ['#0090fc', '#ff6666'])
            ->setFill('gradient')
            ->addDataAreaTraffic('TX Rate', $data->datasets()[1]->toArray(), $data->labels()->toArray(), $data->interfaces()->toArray())
            ->addDataAreaTraffic('RX Rate', $data->datasets()[0]->toArray(), $data->labels()->toArray(), $data->interfaces()->toArray())
            ->setXAxis($data->labels()->toArray())
            ->setGrid(true, '#3F51B5', 0.2)
            //->setToolbar(true)
            ->setColors(['#0090fc', '#ff6666'])
            ->setMarkers(['#0090fc', '#ff6666'], 4, 6);
    }
}

