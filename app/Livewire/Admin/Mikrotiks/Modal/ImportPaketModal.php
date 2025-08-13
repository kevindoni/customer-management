<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use App\Services\PaketService;
use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\Validator;
use App\Services\Mikrotiks\MikrotikService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
//use App\Services\Mikrotiks\MikrotikPppProfilesService;

class ImportPaketModal extends Component
{
    public $importPaketModal = false;
    public $input = [];
    //public $mikrotiks;
    // public $toMikrotik;
    // public $toMikrotik;

    public $mikrotik;
    //public $toMikrotik;

    public $maxProfile = 0;
    public $countDifferentProfile = 0;

    private MikrotikService $mikrotikService;
    //private MikrotikPppProfilesService $mikrotikPppProfilesService;
    private PaketService $paketService;

    public function __construct()
    {
        // Initialize Product
        $this->mikrotikService = new MikrotikService;
        //$this->mikrotikPppProfilesService = new MikrotikPppProfilesService;
        $this->paketService = new PaketService;
    }

    #[On('show-import-paket-modal')]
    public function showImportCustomerModal(Mikrotik $mikrotik)
    {
        $this->reset();
        $this->importPaketModal = true;
        $this->mikrotik = $mikrotik;
        //$this->mikrotiks = Mikrotik::whereDisabled('false')->orderBy('name')->get();
        try {
            $differentProfilesName = $this->paketService->neededCreateProfiles($this->mikrotik);
            $maxProcess = Websystem::first()->max_process;
            env('QUEUE_CONNECTION') == 'sync' ? (count($differentProfilesName) > $maxProcess ? $this->maxProfile = $maxProcess : $this->maxProfile = count($differentProfilesName)) : $this->maxProfile = count($differentProfilesName);
            // $this->maxProfile = count($differentProfilesName);
            $this->countDifferentProfile = count($differentProfilesName);
        } catch (\Exception $e) {
            $this->notification('Failed', $e->getMessage(), 'error');
        }
    }

    /*
    public function updatedInputSelectedServer($mikrotikId)
    {
        //dd($this->mikrotik);
        $this->fromMikrotik = Mikrotik::find($mikrotikId);
        //dd($this->mikrotik->name);
        if ($this->fromMikrotik) {
            //$differentProfilesName = $this->mikrotikPppProfilesService->getDifferentCustomerManagementProfiles($this->mikrotik, $this->fromMikrotik);
            try {
                $differentProfilesName = $this->paketService->neededCreateProfiles($this->fromMikrotik, $this->toMikrotik);
                $maxProcess = Websystem::first()->max_process;
                env('QUEUE_CONNECTION') == 'sync' ? (count($differentProfilesName) > $maxProcess ? $this->maxProfile = $maxProcess : $this->maxProfile = count($differentProfilesName)) : $this->maxProfile = count($differentProfilesName);
                // $this->maxProfile = count($differentProfilesName);
                $this->countDifferentProfile = count($differentProfilesName);
            } catch (\Exception $e) {
                $this->notification('Failed', $e->getMessage(), 'error');
            }
        } else {
            $this->countDifferentProfile = 0;
            $this->maxProfile = 0;
        }

        //dd(count($this->differentUsersSecret));
    }
*/
    public function importPaket()
    {
        try {
            $this->paketService->importPaketsFromMikrotik($this->mikrotik);
            $message = trans('mikrotik.alert.import-profiles-in-progress', ['countProfile' => $this->maxProfile, 'mikrotik' => $this->mikrotik->name]);
            $this->notification(trans('mikrotik.alert.import-profiles'), $message, 'success');
            $this->importPaketModal = false;
        } catch (\Exception $e) {
            $this->notification('Failed', $e->getMessage(), 'error');
            $this->importPaketModal = false;
        }


        //  dispatch(new ImportPaketFromMikrotikJob($this->mikrotik, $fromMikrotik))->onQueue('default');

    }

    public function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function render()
    {
        return view('livewire.admin.mikrotiks.modal.import-paket-modal');
    }
}
