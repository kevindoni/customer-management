<?php

namespace App\Livewire\Admin\Pakets\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use App\Livewire\Actions\Pakets\PaketAction;

class PermanentlyDeletePaket extends Component
{
    use NotificationTrait;
    public $permanentlyDeletePaketModal = false;
    public $paket = null;
    public $input = [];
    public $contentCheckbox;

    /**
     * Delete paketk function
     * Show delete confirmation modal
     */
    #[On('show-delete-paket-permanently-modal')]
    public function showPermanentlyDeleteModal($paketId)
    {
        $this->paket = Paket::withTrashed()->findOrFail($paketId);
        $this->input['checkbox_delete_on_mikrotik'] = false;
        $this->permanentlyDeletePaketModal = true;
    }

    /**
     * Deleted paket after validate user
     */
    public function permanentlyDeletePaket(GeneralLogServices $generalLogServices, PaketAction $paketAction)
    {
        DB::beginTransaction();
        try {
            $paketName = $this->paket->name;
            $mikrotikName = $this->paket->mikrotik->name;
            $checkBoxDeleteOnMikrotik = $this->input['checkbox_delete_on_mikrotik'] ?? false;
            if ($checkBoxDeleteOnMikrotik) {
                dd('delete on mikrotik');
            }

            //$this->paket->forceDelete();
            $generalLogServices->admin_action($generalLogServices::PAKET, "Permanently delete paket " . $paketName . ' on Server ' . $mikrotikName, $generalLogServices::DELETE_PAKET);
            DB::commit();
            $title =  trans('paket.alert.delete-paket', ['paket' => $paketName]);
            $message = trans('paket.alert.delete-paket-permanently-successfully', ['paket' => $paketName, 'mikrotik' => $mikrotikName]);
            $this->notification($title, $message, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $title =  trans('paket.alert.failed');
            $this->notification($title, $e->getMessage(), 'error');
        }
        $this->closeDeletePaketModal();
    }



    /**
     * Clode confirmation modal
     */
    public function closeDeletePaketModal()
    {
        //$this->mikrotik = '';
        $this->dispatch('refresh-deleted-paket-list');
        $this->permanentlyDeletePaketModal = false;

        $this->reset();
    }

    public function updatedInputCheckboxDeleteOnMikrotik($value)
    {
        if ($value) {
            $this->contentCheckbox = trans('paket.alert.delete-permanently');
        } else {
            $this->contentCheckbox = trans('paket.alert.move-to-trash');
        }
    }
    public function render()
    {
        return view('livewire.admin.pakets.modal.permanently-delete-paket');
    }
}
