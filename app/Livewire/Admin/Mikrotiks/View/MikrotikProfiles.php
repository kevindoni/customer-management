<?php

namespace App\Livewire\Admin\Mikrotiks\View;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;
use App\Support\CollectionPagination;
use App\Services\Mikrotiks\MikrotikService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class MikrotikProfiles extends Component
{
    use WithPagination;
    public  $perPage = 20;
    public $online;
    public $pppProfiles;
    private MikrotikService $mikrotikService;

    public function __construct()
    {
        // Initialize Product
        $this->mikrotikService = new MikrotikService;
    }

    public Mikrotik $mikrotik;
    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }


    public function render()
    {
        try {
            $this->pppProfiles = $this->mikrotikService->getPppProfiles($this->mikrotik);
            $this->online = true;
        } catch (\Exception $e) {
            $this->online = false;
            LivewireAlert::title('Failed')
            ->text($e->getMessage())
            ->position('center')
           // ->toast()
            ->status('error')
            ->show();
        }


        $mikrotikProfiles = (new CollectionPagination($this->pppProfiles))->collectionPaginate($this->perPage);

        return view('livewire.admin.mikrotiks.view.mikrotik-profiles', compact('mikrotikProfiles'));
    }
}
