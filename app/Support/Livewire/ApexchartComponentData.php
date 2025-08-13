<?php
namespace App\Support\Livewire;

use App\Models\Servers\Mikrotik;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ChartComponentData
 *
 * @package App\Support\Livewire
 */
class ApexchartComponentData implements Arrayable
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private Collection $labels;

    /**
     * @var \Illuminate\Support\Collection
     */
    private Collection $datasets;

    private Collection $interfaces;
    private Mikrotik $mikrotik;

    /**
     * ChartComponentData constructor.
     *
     * @param \Illuminate\Support\Collection $labels
     * @param \Illuminate\Support\Collection $datasets
     */
    public function __construct(Collection $labels, Collection $datasets, Collection  $interfaces, Mikrotik $mikrotik)
    {
        $this->labels = $labels;
        $this->datasets = $datasets;
        $this->interfaces = $interfaces;
        $this->mikrotik = $mikrotik;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'labels'    => $this->labels,
            'datasets'  => $this->datasets,
            'interfaces'  => $this->interfaces,
            'mikrotik' => $this->mikrotik
        ];
    }

    /**
     * @return string
     */
    public function checksum(): string
    {
        return md5(json_encode($this->toArray()));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function labels(): Collection
    {
        return $this->labels;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function datasets(): Collection
    {
        return $this->datasets;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function interfaces(): Collection
    {
        return $this->interfaces;
    }

    /**
     * @return \App\Models\Servers\Mikrotik
     */
    public function mikrotik(): Mikrotik
    {
        return $this->mikrotik;
    }
}
