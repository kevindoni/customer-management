<?php

namespace App\Livewire\Admin\Mikrotiks\View;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class MikrotikDashboard extends Component
{
    public $mikrotik;
    public $interfaces;
    public $allUserSecrets;
    public $activeSecrets;
    public $profiles;
    public $online;

    private MikrotikService $mikrotikService;

    public function __construct()
    {
        // Initialize Product
        $this->mikrotikService = new MikrotikService;
    }
    public function mount(Mikrotik $mikrotik)
    {
        $this->mikrotik = $mikrotik;
        try {
            $this->allUserSecrets = $this->mikrotikService->getAllUserSecrets($this->mikrotik);

            // return dd($this->allUserSecrets);
            $this->activeSecrets = $this->mikrotikService->getActiveSecrets($this->mikrotik);
            $this->profiles = $this->mikrotikService->getPppProfiles($this->mikrotik);
            $this->interfaces = $this->mikrotikService->mikrotikEtherInterface($this->mikrotik);
            $this->online = true;
        } catch (\Exception $e) {
            $this->online = false;
            $this->notification(trans('mikrotik.alert.error-code',['code'=>$e->getCode()]),  $e->getMessage(), 'error');
        }
    }
    public function render()
    {

        return view('livewire.admin.mikrotiks.view.mikrotik-dashboard');
    }

    public function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
            ->text($message)
            ->position('center')
            //->toast()
            ->status($status)
            ->show();
    }

    ///   public function render()
    //   {
    //     return view('livewire.admin.mikrotiks.view.mikrotik-dashboard');
    //  }
}
