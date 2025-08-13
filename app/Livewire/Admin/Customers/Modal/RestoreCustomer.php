<?php

namespace App\Livewire\Admin\Customers\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\CustomerService;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Services\GeneralLogServices;
use Illuminate\Support\Facades\Auth;
use App\Services\CustomerPaketService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class RestoreCustomer extends Component
{

    use NotificationTrait;
    public $restoreCustomerModal = false;
    public $user;
    public $input = [];

    #[On('restore-customer-modal')]
    public function showRestoreCustomerModal($user)
    {
        $this->reset();
        $this->user = User::withTrashed()->findOrFail($user);
        $this->restoreCustomerModal = true;
    }

    public function restoredCustomer(CustomerService $customerService, GeneralLogServices $generalLogServices)
    {

        //dd($this->user->login_histories->withTrashed());
        DB::beginTransaction();
        //dd($this->user->customer_pakets()->whereNotNull('activation_date')->withTrashed()->get());
        try {

           $customerService->restoreCustomer($this->user, $this->input['restoreOnMikrotik']);
            //$this->user->restore();
            $generalLogServices->admin_action($generalLogServices::RESTORE_CUSTOMER, "Restore customer " . $this->user->full_name);
            DB::commit();

            $this->notification('Restore Success!', 'Restore customer successfully', 'success');
            $this->dispatch('refresh-deleted-customer-list');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notification('Restore Failed!', $e->getMessage(), 'error');
        }


        $this->restoreCustomerModal = false;
    }

    public function render()
    {
        return view('livewire.admin.customers.modal.restore-customer');
    }
}
