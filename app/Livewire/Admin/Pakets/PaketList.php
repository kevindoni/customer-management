<?php

namespace App\Livewire\Admin\Pakets;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use Livewire\WithPagination;
use App\Services\Mikrotiks\MikrotikPppService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use App\Models\Customers\CustomerPaket;

class PaketList extends Component
{
    use WithPagination;
    //Short by
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    //Search
    public $search_name = '';
    public $search_server = '';
    public $search_with_status;
    public $firstRun = true;

    public $showDataLabels = false;
    // Pagination
    public $perPage = 25;

    //dispatch
    public $alert_title, $alert_message;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }


    public function updateProfileScript(Paket $paket)
    {
        (new MikrotikPppService())->updateScriptPppProfile($paket);
    }
    /**
     * render layout
     */
    #[On('model-deleted')] //send from global delete button
    #[On('refresh-paket-list')]
    public function render()
    {
        $pakets = Paket::when($this->search_name, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like',  "%" . $this->search_name . "%");
            });
        })
            ->when($this->search_server, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->where('mikrotik_id', $this->search_server);
                });
            })
            ->when($this->search_with_status, function ($builder) {
                $builder->where(function ($builder) {
                    $this->search_with_status = ($this->search_with_status == "true") ? true : false;
                    $builder->where('disabled', $this->search_with_status);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);


        //Paket Chart
        $expenses = CustomerPaket::all();
            $pieChartModel = $expenses->groupBy('paket_id')
            ->reduce(function ($pieChartModel, $data) {
                $type = $data->first()->paket->name;
                $value = $data->count();

                return $pieChartModel
                ->addSlice($type, $value, []);

            }, LivewireCharts::pieChartModel()
                //->setTitle('Expenses by Type')
                ->setAnimated($this->firstRun)
                ->setType('donut')
                ->withOnSliceClickEvent('onSliceClick')
                //->withoutLegend()
               // ->legendPositionBottom()
               ->legendPositionRight()
                ->legendHorizontallyAlignedCenter()
                ->setDataLabelsEnabled($this->showDataLabels)
               ->setColors(['#19aa02','#03A9F4','#f90704'])
            );


        return view('livewire.admin.pakets.paket-list', [
            'pakets' => $pakets,
            'pieChartModel' => $pieChartModel,
        ])->layout('components.layouts.app', ['title' => trans('system.title.pakets')]);
    }

    /**
     * Alert when paket successfully disable or enable
     */
    #[On('paket-disable')] //send from disable toogle button
    public function alert($model)
    {
        if ($model['disabled'] == true) {
            $alert_title = trans('paket.alert.disable-successfully');
            $alert_message = trans('paket.alert.paket-disable', ['paket' => $model['name']]);
        } else {
            $alert_title = trans('paket.alert.enable-successfully');
            $alert_message = trans('paket.alert.paket-enable', ['paket' => $model['name']]);
        }
        LivewireAlert::title($alert_title)
        ->text($alert_message)
        ->position('top-end')
        ->toast()
        ->status('info')
        ->show();
    }
}
