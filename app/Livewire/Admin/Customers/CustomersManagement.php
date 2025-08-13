<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use App\Traits\NotificationTrait;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CustomersManagement extends Component
{
    use WithPagination, NotificationTrait;
    //Short by
    public $sortField = 'full_name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    //Search
    public $search_name_or_email = '';
    public $search_with_address = '';
    //public $search_gender = '';
    public $usersSelected = [];

    // Pagination
    public $checkAll;
    public $perPage = 25;

    //dispatch
    public $alert_title, $alert_message;

    public $first_name, $last_name, $address, $email, $phone;
    public $state = [];

    public $selectedServer;
    public $search_with_paket = '';
    public $search_with_status_customer_paket = '';
    public $search_with_internet_service = '';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatedSearchAddress()
    {
        $this->resetPage();
    }

    public function updatedSearchNameOrEmail()
    {
        $this->resetPage();
    }

    public function updatedSearchGender()
    {
        $this->resetPage();
    }

    /**
     * Alert when user successfully disable or enable
     */
    #[On('user-disable')]
    public function alert($model)
    {
        $model = User::find($model['id']);
        if ($model->disabled) {
            $this->alert_title = trans('user.alert.disable-successfully');
            $this->alert_message = trans('user.alert.user-disable', ['user' => $model->full_name]);
        } else {
            $this->alert_title = trans('user.alert.enable-successfully');
            $this->alert_message = trans('user.alert.user-enable', ['user' => $model->full_name]);
        }
        $this->dispatch('updated');
    }

    /**
     * Export all user
     */
    public function exportUser()
    {
        //  return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function verificationUser(User $user)
    {
        $user->activation();
        $this->success_notification(trans('customer.alert.success'), trans('customer.alert.verification-user-successfully', ['user' => $user->full_name]));
    }

    #[On('notification-user-disable')]
    public function notification($model)
    {
        $this->success_notification(trans('user.alert.user-created'), trans('user.alert.user-created-successfully', ['user' => $model['first_name']]));
    }

    public function bulkDelete()
    {
        $this->dispatch('bulk-delete-customer-modal', userSelected: $this->selectedUser);
    }

    public function bulkEdit()
    {
        // dd($this->userActions);
        $this->dispatch('bulk-edit-customer-modal', userSelected: $this->usersSelected);
    }

    /* public function updatedSelectAll($value)
    {
      // dd($value);
        if($value){
            $this->selectedUser = json_decode($this->getCustomers()->pluck('id'), true);
        } else {
            $this->selectedUser = [];
        }
         //$selectedUser = $this->getCustomers()->pluck('id');
            //$merged = collect($this->selectedUser)->merge($selectedUser);
            //$this->selectedUser = json_decode(collect($merged->all()), true);

    }
*/
    #[On('refresh-selected-users')]
    public function refreshSelectedCustomerPaket()
    {
        $this->usersSelected = [];
        $this->checkAll = false;
    }


    private function getCustomersWithPakets()
    {
        return User::join('user_customers', 'users.id', 'user_customers.user_id')
            ->join('user_addresses', 'users.id', 'user_addresses.user_id')
            ->with('customer_pakets', function ($customer_pakets) {
                $customer_pakets->when($this->search_with_paket, function ($customer_pakets) {
                    //$this->resetPage();
                    $customer_pakets->where('paket_id', $this->search_with_paket);
                });

                $customer_pakets->when($this->search_with_internet_service, function ($customer_pakets) {
                    //$this->resetPage();
                    $customer_pakets->whereInternetServiceId($this->search_with_internet_service);
                });

                $customer_pakets->when($this->search_with_status_customer_paket, function ($customer_pakets) {
                    // $this->resetPage();
                    if ($this->search_with_status_customer_paket == "online") {
                        $customer_pakets->where('online', true);
                    } else if ($this->search_with_status_customer_paket == "offline") {
                        $customer_pakets->where('online', false);
                    } else {
                        $customer_pakets->where('status', $this->search_with_status_customer_paket);
                    }
                });
            })
            ->when($this->search_with_paket, function ($q) {
                //$this->resetPage();
                $q->whereHas('customer_pakets', function ($customer_pakets) {
                    $customer_pakets->where('paket_id', $this->search_with_paket);
                });
            })
            ->when($this->search_with_status_customer_paket, function ($q) {
                //$this->resetPage();
                $q->whereHas('customer_pakets', function ($customer_pakets) {
                    if ($this->search_with_status_customer_paket == "online") {
                        $customer_pakets->where('online', true);
                    } else if ($this->search_with_status_customer_paket == "offline") {
                        $customer_pakets->where('online', false);
                    } else {
                        $customer_pakets->where('status', $this->search_with_status_customer_paket);
                    }
                });
            })
            ->when($this->selectedServer, function ($q) {
                $q->whereHas('customer_pakets', function ($customer_pakets) {
                    $customer_pakets->whereHas('paket', function ($customer_pakets) {
                        $customer_pakets->where('mikrotik_id', $this->selectedServer);
                    });
                });
            })
            ->when($this->search_with_internet_service, function ($q) {
                //$this->resetPage();
                $q->whereHas('customer_pakets', function ($customer_pakets) {
                    $customer_pakets->whereInternetServiceId($this->search_with_internet_service);
                });
            })
            ->when($this->search_with_address, function ($q) {
                //$this->resetPage();
                $q->where('address', 'like', '%' . $this->search_with_address . '%')
                    ->orWhere('phone', 'like', '%' . $this->search_with_address . '%');
            })

            ->when($this->search_name_or_email, function ($q) {
                 $q->where(function ($builder) {
                        $sql = "CONCAT(users.first_name,' ',COALESCE(users.last_name,''))  like ?";
                        $builder->whereRaw($sql,  "%" . $this->search_name_or_email . "%")
                            ->orWhere('email', 'like', '%' . $this->search_name_or_email . '%');
                    });
            })

            ->select('user_addresses.id as user_address_id', DB::raw("CONCAT(users.first_name,' ',COALESCE(users.last_name,'')) as full_name"), 'users.*', 'user_addresses.*')
            ->orderBy($this->sortField, $this->sortDirection)

            ->paginate($this->perPage);
    }

    #[On('refresh-customer-list')]
    public function render()
    {
        $customers = $this->getCustomersWithPakets();
        return view('livewire.admin.customers.customers-management', [
            'customers' => $customers
        ])->layout('components.layouts.app', ['title' => trans('system.title.customers')]);
    }
}
