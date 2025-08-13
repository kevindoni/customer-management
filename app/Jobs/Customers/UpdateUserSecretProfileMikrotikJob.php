<?php

namespace App\Jobs\Customers;

use App\Models\Pakets\Paket;
use App\Models\Servers\Mikrotik;
use App\Livewire\Actions\Pakets\PaketAction;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Services\PaketProfileService;

class UpdateUserSecretProfileMikrotikJob implements ShouldQueue
{
    use Queueable;

  //  private Mikrotik $mikrotik;
   // private $username;
  //  private  $profileName;
   // private MikrotikPppService $pppService;
    private Paket $paket;
    private $profileID;
    /**
     * Create a new job instance.
     */

    //public function __construct(Mikrotik $mikrotik, $username, $profileName)
    public function __construct(Paket $paket, $profileID)
    {
        //$this->mikrotik = $mikrotik;
        //$this->username = $username;
        // $this->profileName = $profileName;
      //  $this->pppService = new MikrotikPppService;
        $this->paket = $paket;
        $this->profileID = $profileID;
    }

    /**
     * Execute the job.
     */
    public function handle(PaketProfileService $paketProfileService): void
    {
        //(new PaketAction())->update_user_profile_in_mikrotik($this->paket, $this->profileID);
        $paketProfileService->update_user_profile_in_mikrotik($this->paket, $this->profileID);
    }
}
