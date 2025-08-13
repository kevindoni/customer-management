<?php

namespace App\Livewire\Admin\Mikrotiks\View;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;
use App\Support\CollectionPagination;

class MikrotikPakets extends Component
{
    use WithPagination;

    public  $perPage = 20;
    public $pppProfiles;
    public Mikrotik $mikrotik;
    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }
    public function render()
    {
        $pakets = $this->mikrotik->pakets;
        $pakets = (new CollectionPagination($pakets))->collectionPaginate($this->perPage);
        // return dd($paket_profiles);
        return view('livewire.admin.mikrotiks.view.mikrotik-pakets', compact('pakets'));
    }
}
