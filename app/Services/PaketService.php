<?php

namespace App\Services;

use App\Models\Websystem;
use App\Models\Pakets\Paket;
use App\Models\Servers\Mikrotik;
use App\Models\Pakets\PaketProfile;
use App\Support\CollectionPagination;
use App\Services\Mikrotiks\MikrotikService;
use App\Livewire\Actions\Pakets\PaketAction;
use App\Jobs\Pakets\ExportPaketToMikrotikJob;
use App\Jobs\Pakets\ImportPaketFromMikrotikJob;
use App\Http\Resources\Mikrotik\ProfileResource;
use App\Services\Mikrotiks\MikrotikPppService;

class PaketService
{
    private MikrotikService $mikrotikService;


    public function __construct()
    {
        $this->mikrotikService = new MikrotikService;
    }

    public function neededCreateProfiles(Mikrotik $mikrotik)
    {
        $mikrotikProfiles = $this->mikrotikService->getPppProfiles($mikrotik);

        //Create paket if profile doesnt have paket and needed to import customer
       // $paketProfileDontHavePaket = PaketProfile::whereDoesntHave('pakets')->pluck('profile_name');
        $paketProfileDontHavePaket  = PaketProfile::whereDoesntHave(
            'pakets',
            function ($pakets) use ($mikrotik) {
                $pakets->where('mikrotik_id', $mikrotik->id);
            }
        )->pluck('profile_name');
        $mikrotikProfileNeededPakets = collect($mikrotikProfiles)->whereIn('name', $paketProfileDontHavePaket);
        foreach ($mikrotikProfileNeededPakets as $mikrotikProfileNeededPaket) {
            $mikrotikProfileNeededPaket = new ProfileResource($mikrotikProfileNeededPaket);

            $paketProfile = PaketProfile::whereProfileName($mikrotikProfileNeededPaket['name'])->first();
            $paket = (new PaketAction())->importPaket($mikrotik->id, $paketProfile, $mikrotikProfileNeededPaket['comment'] ?? 0);
            $paket->forceFill([
                'mikrotik_ppp_profile_id' => $mikrotikProfileNeededPaket['.id']
            ])->save();
        }

        $paketProfiles = PaketProfile::pluck('profile_name');
        return collect($mikrotikProfiles)->whereNotIn('name', $paketProfiles);
    }

    public function importPaketsFromMikrotik(Mikrotik $mikrotik)
    {
        $neededCreateProfiles = $this->neededCreateProfiles($mikrotik);
        //Import secrets from mikrotik to customer management
        //To reduce server work processes, secret imports are limited according to settings if the queue connection is in sync mode.
        if (env('QUEUE_CONNECTION') == 'sync') {
            $neededCreateProfiles =   (new CollectionPagination($neededCreateProfiles))->collectionPaginate(Websystem::first()->max_process);
        }
        foreach ($neededCreateProfiles as $neededCreateProfile) {
            dispatch(new ImportPaketFromMikrotikJob($mikrotik, $neededCreateProfile))->onQueue('default');
        }
    }

    public function neededExportProfiles($fromMikrotik, Mikrotik $toMikrotik)
    {
        $mikrotikProfiles = $this->mikrotikService->getPppProfiles($toMikrotik);
        //$paketProfiles = PaketProfile::pluck('profile_name');
        $paketProfiles = PaketProfile::whereHas(
            'pakets',
            function ($pakets) use ($fromMikrotik) {
                $pakets->where('mikrotik_id', $fromMikrotik->id);
            }
        )->get();
        return collect($paketProfiles)->whereNotIn('profile_name', collect($mikrotikProfiles)->pluck('name'));
    }

    public function exportPaketsToMikrotik($fromMikrotik, Mikrotik $toMikrotik)
    {
       $neededExportProfiles = $this->neededExportProfiles($fromMikrotik, $toMikrotik);
        //Export paket from Customer Management to mikrotik
        //To reduce server work processes, secret imports are limited according to settings if the queue connection is in sync mode.
       if (env('QUEUE_CONNECTION') == 'sync') {
           $neededExportProfiles =   (new CollectionPagination($neededExportProfiles))->collectionPaginate(Websystem::first()->max_process);
     }

        foreach ($neededExportProfiles as $neededExportProfile) {
            dispatch(new ExportPaketToMikrotikJob($toMikrotik, $neededExportProfile))->onQueue('default');
        }
    }


}
