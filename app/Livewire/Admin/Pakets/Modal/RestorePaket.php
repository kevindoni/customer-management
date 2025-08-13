<?php

namespace App\Livewire\Admin\Pakets\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;

class RestorePaket extends Component
{
    use NotificationTrait;

    public $restorePaketModal = false;
    public $paket;
    public $input = [];

    #[On('restore-paket-modal')]
    public function showRestorePaketModal($paketId)
    {
        $this->reset();
        $this->paket = Paket::withTrashed()->findOrFail($paketId);
        $this->restorePaketModal = true;
    }

    public function restoredPaket(GeneralLogServices $generalLogServices)
    {
        DB::beginTransaction();
        try {
            $this->paket->restore();
            $generalLogServices->admin_action($generalLogServices::PAKET, "Restore paket " . $this->paket->name . ' on Server ' . $this->paket->mikrotik->name, $generalLogServices::RESTORE_PAKET);
            DB::commit();

            $this->notification('Restore Success!', 'Restore paket successfully', 'success');

            $this->dispatch('refresh-deleted-paket-list');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notification('Restore Failed!', $e->getMessage(), 'error');
        }

        $this->restorePaketModal = false;
    }
    public function render()
    {
        return view('livewire.admin.pakets.modal.restore-paket');
    }
}
