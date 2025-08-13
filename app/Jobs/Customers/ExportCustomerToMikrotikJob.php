<?php

namespace App\Jobs\Customers;

use Carbon\Carbon;
use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\Log;
use App\Models\Customers\CustomerPppPaket;
use App\Models\Websystem;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Mikrotiks\MikrotikPppService;



class ExportCustomerToMikrotikJob implements ShouldQueue
{
    use Queueable;

    private Mikrotik $toMikrotik;
    private $comment;
    private CustomerPppPaket $customerPppPaket;
    /**
     * Create a new job instance.
     */
    public function __construct($toMikrotik, $customerPppPaket)
    {
        $this->customerPppPaket = $customerPppPaket;
        $this->toMikrotik = $toMikrotik;
    }

    /**
     * Execute the job.
     */
    public function handle(MikrotikPppService $mikrotikPppService): void
    {
        $customerPaket = $this->customerPppPaket->customer_paket;
        if ($this->toMikrotik->auto_isolir->isolir_driver === 'mikrotik') {
            $websystem = Websystem::first();
            $commentMikrotik = $websystem->comment_unpayment . '_' .  Carbon::parse($customerPaket->expired_date)->format('d_m_y');
        } else {
            $commentMikrotik = 'create_from_customer_management_v2';
        }

        $response = $mikrotikPppService->createSecret(
            $this->toMikrotik,
            $customerPaket,
            $customerPaket->disabled ? 'true' : 'false',
            $commentMikrotik
        );

        if ($response['success']) {
            $this->customerPppPaket->update([
                'secret_id' => $response['secret_id']
            ]);
        } else {
            Log::error($response['message']);
        }
    }
}
