<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class DeletedCustomerPaketManagement extends Component
{
    use WithPagination;
    //Short by
    public $sortField = 'full_name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];
    //Search
    public $search_name_or_email = '';
    public $search_address = '';
    public $selectedServer;
    public $selectedCustomerPaket = [];
    public $checkAll;
    // Pagination
    public $perPage = 25;
    //dispatch
    public $alert_title, $alert_message;
    public $first_name, $last_name, $address, $email, $phone;

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

    /*
    public function bulkDeletePermanently()
    {
        //$this->dispatch('bulk-delete-customer-paket-modal', customerPaketSelected: $this->selectedCustomerPaket);
    }

    public function bulkRestore()
    {
        //$this->dispatch('bulk-edit-activation-paket-modal', customerPaketSelected: $this->selectedCustomerPaket);
    }
*/
    #[On('refresh-selected-customer-pakets')]
    public function refreshSelectedCustomerPaket()
    {
        $this->selectedCustomerPaket = [];
        $this->checkAll = false;
    }

public function getDeletedCustomerPaket()
{
    return User::select("id", "first_name", "last_name", DB::raw("CONCAT(users.first_name,' ',COALESCE(users.last_name, '')) as full_name"), "email", "username", "email_verified_at", "disabled")
            ->when($this->search_name_or_email, function ($builder) {
                //$this->resetPage();
                $builder->where(function ($builder) {
                    $sql = "CONCAT(users.first_name,' ',COALESCE(users.last_name,''))  like ?";
                    $builder->whereRaw($sql,  "%" . $this->search_name_or_email . "%")
                        ->orWhere('email', 'like', '%' . $this->search_name_or_email . '%');
                });
            })
            ->with('user_customer')
            ->with('user_address')
            ->whereHas('user_address', function ($builder) {
                $builder->when($this->search_address, function ($builder) {
                    $builder->where(function ($builder) {
                        $builder->where('address', 'like', '%' . $this->search_address . '%')
                            ->orWhere('phone', 'like', '%' . $this->search_address . '%');
                    });
                });
            })
            ->with('customer_pakets', function ($customer_pakets) {
                $customer_pakets->when($this->selectedServer, function ($customer_pakets) {
                    // $customer_pakets->paket()->where('mikrotik_id', $this->selectedServer);
                })->onlyTrashed()->whereHas('paket');
            })

            ->whereHas('customer_pakets', function ($customer_pakets) {
                $customer_pakets->when($this->selectedServer, function ($customer_pakets) {
                    // $customer_pakets->paket()->where('mikrotik_id', $this->selectedServer);
                })->onlyTrashed()->whereHas('paket');
            })
            //->withTrashed()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
}

    #[On('refresh-deleted-customer-paket-list')]
    public function render()
    {
        /*
        $customers = User::select("id", "first_name", "last_name", DB::raw("CONCAT(users.first_name,' ',COALESCE(users.last_name, '')) as full_name"), "email", "username", "email_verified_at", "disabled")
            ->when($this->search_name_or_email, function ($builder) {
                //$this->resetPage();
                $builder->where(function ($builder) {
                    $sql = "CONCAT(users.first_name,' ',COALESCE(users.last_name,''))  like ?";
                    $builder->whereRaw($sql,  "%" . $this->search_name_or_email . "%")
                        ->orWhere('email', 'like', '%' . $this->search_name_or_email . '%');
                });
            })
            ->with('user_customer')->withTrashed()
            ->with('user_address')->withTrashed()
            ->whereHas('user_address', function ($builder) {
                $builder->when($this->search_address, function ($builder) {
                    $builder->where(function ($builder) {
                        $builder->where('address', 'like', '%' . $this->search_address . '%')
                            ->orWhere('phone', 'like', '%' . $this->search_address . '%');
                    });
                })->withTrashed();
            })
            ->with('customer_pakets', function ($customer_pakets) {
                $customer_pakets->when($this->selectedServer, function ($customer_pakets) {
                    // $customer_pakets->paket()->where('mikrotik_id', $this->selectedServer);
                })->onlyTrashed();
            })

            ->whereHas('customer_pakets', function ($customer_pakets) {
                $customer_pakets->when($this->selectedServer, function ($customer_pakets) {
                    // $customer_pakets->paket()->where('mikrotik_id', $this->selectedServer);
                })->onlyTrashed();
            })
            ->withTrashed()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            */

        $customers = $this->getDeletedCustomerPaket();

        return view('livewire.admin.customers.deleted-customer-paket-management', [
            'customers' => $customers
        ])->layout('components.layouts.app', ['title' => trans('system.title.customers')]);
    }
}
