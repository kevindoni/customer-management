<?php

namespace App\Livewire\Admin\Pakets;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeletedPaket extends Component
{
    use WithPagination;
    //Short by
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    //Search
    public $search_name = '';
    public $search_server = '';
    public $search_with_status;

    // Pagination
    public $perPage = 25;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    /**
     * render layout
     */
    #[On('refresh-deleted-paket-list')]
    public function render()
    {

        $deletedPakets = Paket::when($this->search_name, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like',  "%" . $this->search_name . "%");
            });
        })
        ->with('mikrotik')
        ->whereHas('mikrotik')
            ->when($this->search_server, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->where('mikrotik_id', $this->search_server);
                });
            })
            ->when($this->search_with_status, function ($builder) {
                $builder->where(function ($builder) {
                    $this->search_with_status = ($this->search_with_status == "true") ? true : false;
                    $builder->where('disabled', $this->search_with_status);
                });
            })
            ->onlyTrashed()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

          //  $deletedPakets = Mikrotik::with('paket')->get();
        return view('livewire.admin.pakets.deleted-paket', [
            'deletedPakets' => $deletedPakets,
        ])->layout('components.layouts.app', ['title' => trans('system.title.deleted-pakets')]);
    }
}
