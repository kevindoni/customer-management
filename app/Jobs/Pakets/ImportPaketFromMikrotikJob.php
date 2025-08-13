<?php

namespace App\Jobs\Pakets;

use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Livewire\Actions\Pakets\PaketAction;
use App\Http\Resources\Mikrotik\ProfileResource;

class ImportPaketFromMikrotikJob implements ShouldQueue
{
    use Queueable;

    private Mikrotik $mikrotik;
    public $mikrotikProfile;
    /**
     * Create a new job instance.
     */
    public function __construct(Mikrotik $mikrotik, $mikrotikProfile)
    {
        $this->mikrotik = $mikrotik;
        $this->mikrotikProfile = $mikrotikProfile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // DB::beginTransaction();
        // try {
        //$mikrotikProfile = ProfileResource::collection($this->mikrotikProfile);
        $mikrotikProfile = new ProfileResource($this->mikrotikProfile);
        $paketProfile = (new PaketAction())->importProfile($mikrotikProfile);
        $paket = (new PaketAction())->importPaket($this->mikrotik->id, $paketProfile, $mikrotikProfile['comment'] ?? 0);
        $paket->forceFill([
            'mikrotik_ppp_profile_id' => $mikrotikProfile['.id']
        ])->save();
        //    DB::commit();
        // } catch (\Exception $e) {
        //    DB::rollBack();
        //    Log::info($e->getMessage());
        //}
    }
}
