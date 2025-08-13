<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use App\Services\CustomerPaketService;
use Illuminate\Support\Facades\Validator;
use App\Livewire\Actions\Users\UserAction;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteMikrotik extends Component
{
    use NotificationTrait;
    public $deleteMikrotikModal = false;
    public $input = [];
    public $mikrotik;
    public $countSecret = 0;

    #[On('show-delete-mikrotik-modal')]
    public function showAddPaketModal(Mikrotik $mikrotik)
    {
        $this->deleteMikrotikModal = true;
        $this->mikrotik = $mikrotik;
        $this->countSecret = $mikrotik->customer_pakets->count();
    }

    public function delete(CustomerPaketService $customerPaketService, GeneralLogServices $generalLogServices)
    {
        Validator::make($this->input, [
            'current_password' => ['required', 'string', 'current_password:web'],
        ])->validate();

        DB::beginTransaction();
        try {
            $mikrotikName = $this->mikrotik->name;
            $customerPakets = $this->mikrotik->customer_pakets;
            $users = User::whereIn('id', $this->mikrotik->customer_pakets()->pluck('user_id'))->get();

            foreach ($customerPakets as $customerPaket) {
                $customerPaketService->disableCustomerPaketOnMikrotik($customerPaket, 'true');
            }

             foreach ($users as $user) {
                (new UserAction())->delete($user);
            }

            $this->mikrotik->delete();

            $generalLogServices->admin_action($generalLogServices::MIKROTIK, "Delete server " . $mikrotikName, $generalLogServices::DELETE_MIKROTIK);

            DB::commit();

            $title =  trans('mikrotik.alert.success');
            $message = trans('mikrotik.alert.success-deleted', ['mikrotik' => $mikrotikName]);
            $this->notification($title, $message, 'success');
        } catch (\Exception $e) {

            DB::rollBack();

            $title =  trans('mikrotik.alert.failed');
            $this->notification($title, $e->getMessage(), 'error');
        }

        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->dispatch('refresh-mikrotik-list');
        $this->deleteMikrotikModal = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.mikrotiks.modal.delete-mikrotik');
    }
}
