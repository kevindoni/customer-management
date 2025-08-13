<?php

namespace App\Exports\Billing;

use App\Models\Websystem;
use App\Models\BillingPaket;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BillingsExport implements FromView
{

    private $billingIDs;
    public function __construct($billingIDs)
    {
        // Initialize
        $this->billingIDs = $billingIDs;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {


        $webSystem = Websystem::where('slug', 'websystem')->first();
        return view('exports.billing.billings-export-to-exel', [
            'billings' =>  $this->billingIDs,
            'websystem' => $webSystem
        ]);
    }
}
