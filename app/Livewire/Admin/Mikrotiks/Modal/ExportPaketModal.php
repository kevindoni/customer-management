<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use App\Services\PaketService;
//use Illuminate\Support\Facades\Cache;
use App\Models\Servers\Mikrotik;
//use App\Services\Mikrotiks\MikrotikService;
use App\Support\CollectionPagination;
use Illuminate\Support\Facades\Validator;
use App\Jobs\Pakets\ExportProfileToMikrotikJob;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
//use App\Services\Mikrotiks\MikrotikPppProfilesService;

class ExportPaketModal extends Component
{
    public $exportPaketModal = false;
    public $input = [];
    public $mikrotiks;
    public $fromMikrotik;
    public $toMikrotik;

    public $maxProfile = 0;
    public $countDifferentProfile = 0;
    public $online  = false;

    private PaketService $paketService;
    public function __construct()
    {
        $this->paketService = new PaketService;
    }


    #[On('show-export-paket-modal')]
    public function showExportPaketModal(Mikrotik $mikrotik)
    {
        $this->reset();
        $this->exportPaketModal  = true;
        $this->fromMikrotik = $mikrotik;
        $this->mikrotiks = Mikrotik::whereDisabled('false')->orderBy('name')->get();
    }

    public function updatedInputSelectedServer($value)
    {
        $this->toMikrotik = Mikrotik::find($value);
        if ($this->toMikrotik) {
            try {
                $paketProfiles = $this->paketService->neededExportProfiles($this->fromMikrotik, $this->toMikrotik);
                $this->countDifferentProfile = count($paketProfiles);
                if (env('QUEUE_CONNECTION') == 'sync') {
                    $paketProfiles =   (new CollectionPagination($paketProfiles))->collectionPaginate(Websystem::first()->max_process);
                }
                $this->maxProfile = count($paketProfiles);
                $this->online = true;
            } catch (\Exception $e) {
                $this->online = false;
                $this->notification('Failed', $e->getMessage(), 'error');
            }
        } else {
            $this->countDifferentProfile = 0;
            $this->maxProfile = 0;
        }
    }

    public function exportPaket()
    {
        Validator::make($this->input, [
            'selectedServer' => ['required'],
        ])->validate();

        try {
            $this->paketService->exportPaketsToMikrotik($this->fromMikrotik, $this->toMikrotik);
            $message = trans('mikrotik.alert.export-profiles-in-progress', ['countProfile' => $this->countDifferentProfile, 'mikrotik' => $this->toMikrotik->name]);
            $this->notification(trans('mikrotik.alert.export-profiles'), $message, 'success');
        } catch (\Exception $e) {
            $this->notification('Failed', $e->getMessage(), 'error');
        }
        $this->exportPaketModal = false;
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
        return view('livewire.admin.mikrotiks.modal.export-paket-modal');
    }
}
