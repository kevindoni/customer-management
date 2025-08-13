<?php

namespace App\Livewire\Admin\WhatsappGateway;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\WhatsappGateway\WhatsappBootMessage;

class BootMessageController extends Component
{
    use WithPagination;

    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    // Pagination
    public $perPage = 10;

    public $search_message;
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


    #[On('refresh-message-wa-list')]
    public function render()
    {
        $bootMessages = WhatsappBootMessage::paginate($this->perPage);
        return view('livewire.admin.whatsapp-gateway.boot-message-controller', compact('bootMessages'));
    }
}
