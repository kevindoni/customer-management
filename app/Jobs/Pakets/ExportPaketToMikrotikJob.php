<?php

namespace App\Jobs\Pakets;

use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Services\PaketService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
//use App\Services\Mikrotiks\MikrotikPppProfilesService;

class ExportPaketToMikrotikJob implements ShouldQueue
{
    use Queueable;

    private Mikrotik $toMikrotik;
    private $profile;

    private MikrotikPppService $mikrotikPppService;
    /**
     * Create a new job instance.
     */
    public function __construct(Mikrotik $toMikrotik, $profile)
    {
        //$this->mikrotikPppProfileService = app(MikrotikPppProfilesService::class);
        $this->mikrotikPppService = new MikrotikPppService;
       // $this->fromMikrotik = $fromMikrotik;
        $this->toMikrotik = $toMikrotik;
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->mikrotikPppService->createSimplePppProfile(
                $this->toMikrotik,
                $this->profile
            );

            if (isset($response['after']['ret'])) {
               $this->profile->paket->forceFill([
                'mikrotik_ppp_profile_id' => $response['after']['ret']
               ])->save();
            }
    }
}
