<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use App\Models\Billings\Invoice;
use App\Models\Customers\CustomerPaket;
use App\Services\GeneralLogServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $firstRun = true;

    public $showDataLabels = false;

    public $years;
    public $search_with_year;
    public $types = ['status_online', 'activation'];

    public function render(GeneralLogServices $generalLogServices)
    {
       // $customerPaket = CustomerPaket::first();
       // $generalLogServices->create_invoice($customerPaket);
        $this->years = Invoice::selectRaw('YEAR(periode) AS year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $billingPakets = Invoice::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(periode) as month_name"))
            ->whereYear('periode',  $this->search_with_year ?? date('Y'))
            ->groupBy(DB::raw("MONTH(periode)"))
            ->get();

        $billingChart = $billingPakets
            ->reduce(
                function ($billingChart, $data) {
                    // Perbaiki logic filtering - gunakan filter yang tepat
                    $monthNumber = Carbon::parse($data->month_name)->month;
                    $unpayment = Invoice::whereYear('periode', $this->search_with_year ?? date('Y'))
                        ->whereMonth('periode', $monthNumber)
                        ->where('status', '!=', 'paid')
                        ->count();
                    $payment = Invoice::whereYear('periode', $this->search_with_year ?? date('Y'))
                        ->whereMonth('periode', $monthNumber)
                        ->where('status', 'paid')
                        ->count();

                    return $billingChart
                        ->addSeriesColumn('Unpayment', $data->month_name, $unpayment)->addColor('#f90704')
                        ->addSeriesColumn('Payment', $data->month_name, $payment)->addColor('#19aa02');
                },
                LivewireCharts::multiColumnChartModel()
                    ->setAnimated($this->firstRun)
                    ->setDataLabelsEnabled(true)
                    ->withOnColumnClickEventName('onColumnClick')
                    ->setTitle('Billing per Year (Month)')
                    ->stacked()
                    ->withGrid()
                    ->withLegend()
                    ->setOpacity(0.7)
            );

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

        return view('livewire.admin.dashboard', [
            'billingChart' => $billingChart,
            'billingPakets' => $billingPakets,
            'pieChartModel' => $pieChartModel,
        ])->layout('components.layouts.app', [
            'title' => trans('system.title.dashboard')
        ]);
    }
}
