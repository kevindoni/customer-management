<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use App\Services\CustomerPaketService;
use Illuminate\Support\Facades\Validator;

class PermanentlyDeleteMikrotik extends Component
{
    use NotificationTrait;
    public $permanentlyDeleteMikrotikModal = false;
    public $input = [];
    public $mikrotik;
    public $countSecret = 0;

    #[On('show-permanently-delete-mikrotik-modal')]
    public function showAddPaketModal($mikrotikId)
    {
        $this->permanentlyDeleteMikrotikModal = true;
        $this->input['checkbox_delete_secret_on_mikrotik'] = false;
        $this->mikrotik = Mikrotik::withTrashed()->findOrFail($mikrotikId);
    }

    public function delete(CustomerPaketService $customerPaketService, GeneralLogServices $generalLogServices)
    {
        Validator::make($this->input, [
            'current_password' => ['required', 'string', 'current_password:web'],
        ])->validate();

        DB::beginTransaction();
        try {
            $mikrotikName = $this->mikrotik->name;
            $customerPakets = $this->mikrotik->customer_pakets()->whereNotNull('activation_date')->get();

            foreach ($customerPakets as $customerPaket) {
                if ($this->input['checkbox_delete_secret_on_mikrotik']) {
                    $customerPaketService->delete_user_on_mikrotik($customerPaket);
                } else {
                    $customerPaketService->disableCustomerPaketOnMikrotik($customerPaket, true);
                }
            }

            $this->mikrotik->delete();
            $generalLogServices->admin_action($generalLogServices::MIKROTIK, "Permanently delete server " . $mikrotikName, $generalLogServices::PERMANENTLY_DELETE_MIKROTIK);
            DB::commit();
            $title =  trans('mikrotik.alert.success');
            $message = trans('mikrotik.alert.success-deleted', ['mikrotik' => $mikrotikName]);
            $this->notification($title, $message, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $title =  trans('mikrotik.alert.failed');
            $message = trans('mikrotik.alert.failed-deleted', ['mikrotik' => $mikrotikName]);
            $this->notification($title, $message, 'error');
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        //$this->mikrotik = '';
        $this->dispatch('refresh-deleted-mikrotik-list');
        $this->permanentlyDeleteMikrotikModal = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.mikrotiks.modal.permanently-delete-mikrotik');
    }
}
