<?php

namespace App\Livewire\Admin\Customers;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeletedCustomersManagement extends Component
{
     use WithPagination;
    //Short by
    public $sortField = 'full_name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    //Search
    public $search_name_or_email = '';
    public $search_address = '';
    public $search_gender = '';
    // public $search_with_paket = '';
   // public $search_with_status_customer_paket = '';
    //public $search_with_internet_service = '';
    // public $selectedServer;
    public $selectedUser = [];
    //public $selectAll = false;
    // public $selectAll;
    //public $selectAll = [];
    // Pagination
    public $checkAll;
    public $perPage = 25;

    //dispatch
    public $alert_title, $alert_message;

    public $first_name, $last_name, $address, $email, $phone;
    public $state = [];

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

    public function bulkDeletePermanent()
    {
        $this->dispatch('bulk-delete-permanent-customer-modal', userSelected: $this->selectedUser);
    }

    public function bulkRestoreCustomer()
    {
        $this->dispatch('bulk-restore-customer-modal', userSelected: $this->selectedUser);
    }

    #[On('refresh-selected-users')]
    public function refreshSelectedCustomerPaket()
    {
        $this->selectedUser = [];
        $this->checkAll = false;
    }

    private function getDeletedCustomers()
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
            ->with('user_customer')->withTrashed()
            ->whereHas('user_customer', function ($builder) {
                $builder->when($this->search_gender, function ($builder) {
                    //$this->resetPage();
                    $builder->where(function ($builder) {
                        $builder->where('gender', $this->search_gender);
                    });
                })->withTrashed();
            })
            ->with('user_address')->withTrashed()
            ->whereHas('user_address', function ($builder) {
                $builder->when($this->search_address, function ($builder) {
                    //$this->resetPage();
                    $builder->where('address', 'like', '%' . $this->search_address . '%')
                        ->orWhere('phone', 'like', '%' . $this->search_address . '%');
                })->withTrashed();
            })
            ->onlyTrashed()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[On('refresh-deleted-customer-list')]
    public function render()
    {
        $customers = $this->getDeletedCustomers();


        return view('livewire.admin.customers.deleted-customers-management' , [
            'customers' => $customers
        ])
        ->layout('components.layouts.app', ['title' => trans('system.title.customers')]);
    }
}
