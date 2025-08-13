<?php

namespace App\Support\Livewire;

use Livewire\Component;
use Illuminate\View\View;
use App\Charts\TrafficMonitoringApexchart;

/**
 * Class ChartComponent
 *
 * @package App\Support\Livewire
 */
abstract class ApexchartComponent extends Component
{

    /**
     * @var string|null
     */
    public ?string $chart_id = null;

    /**
     * @var string|null
     */
    public ?string $chart_data_checksum = null;

    /**
     * @return string
     */
    protected abstract function chartClass(): string;

    /**
     * @return \App\Support\Livewire\ChartComponentData
     */
    protected abstract function chartData(): ApexchartComponentData;

    /**
     * @return string
     */
    protected abstract function view(): string;

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $chart_data = $this->chartData();
        if (!$this->chart_id) {
            $chart =  (new TrafficMonitoringApexchart())->build($chart_data);
            $this->chart_id = $chart->id;
            $this->dispatch('chartLoad');
        } elseif ($chart_data->checksum() !== $this->chart_data_checksum) {
            $this->dispatch('chartUpdate', $this->chart_id, $chart_data->labels(), $chart_data->datasets());
        }
        $this->chart_data_checksum = $chart_data->checksum();

        return view($this->view(), [
            'chart' => ($chart ?? null),
        ]);
    }
}
