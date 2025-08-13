<?php

namespace App\Livewire\Admin\Mikrotiks;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;

class DeletedMikrotiksManagement extends Component
{
    use WithPagination;
    //Short by
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];
    //Search
    public $search_name_or_ip = '';
    public $perPage = 10;
    public $alert_title, $alert_message;

    /**
     * Sort by function
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    #[On('refresh-deleted-mikrotik-list')]
    public function render()
    {
        $mikrotiks = Mikrotik::when($this->search_name_or_ip, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like',  "%" . $this->search_name_or_ip . "%")
                    ->orWhere('host', 'like', '%' . $this->search_name_or_ip . '%');
            });
        })
            ->onlyTrashed()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.mikrotiks.deleted-mikrotiks-management', [
            'mikrotiks' => $mikrotiks
        ]);
    }
}
