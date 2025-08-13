<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;

class RestoreDeletedMikrotik extends Component
{
    use NotificationTrait;

    public $restoreDeletedMikrotikModal = false;
    public $mikrotik;
    public $input = [];

    #[On('show-restore-mikrotik-modal')]
    public function showRestoreMikrotikModal($mikrotikId)
    {
        $this->reset();
        $this->mikrotik = Mikrotik::withTrashed()->findOrFail($mikrotikId);
        $this->restoreDeletedMikrotikModal = true;
    }

    public function restoredMikrotik(GeneralLogServices $generalLogServices)
    {
        DB::beginTransaction();
        try {
            $this->mikrotik->restore();
            $generalLogServices->admin_action($generalLogServices::MIKROTIK, "Restore server " . $this->mikrotik->name, $generalLogServices::RESTORE_MIKROTIK);
            DB::commit();

            $this->notification('Restore Success!', 'Restore mikrotik successfully', 'success');

            $this->dispatch('refresh-deleted-mikrotik-list');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notification('Restore Failed!', $e->getMessage(), 'error');
        }

        $this->restoreDeletedMikrotikModal = false;
    }


    public function render()
    {
        return view('livewire.admin.mikrotiks.modal.restore-deleted-mikrotik');
    }
}
