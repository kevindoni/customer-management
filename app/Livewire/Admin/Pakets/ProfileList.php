<?php

namespace App\Livewire\Admin\Pakets;


use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\PaketProfile;
use Livewire\WithPagination;

class ProfileList extends Component
{
    use WithPagination;


    //Search
    public $search_name = '';
    public $search_server = '';
    public $search_with_status;


    // Pagination
    public $perPage = 10;

    //Modal
    public $addProfileModal = false;

    /**
     * render layout
     */
    #[On('refresh-paket-profile')]
    public function render()
    {
        $paketProfiles = PaketProfile::when($this->search_name, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('profile_name', 'like',  "%" . $this->search_name . "%");
            });
        })
            ->when($this->search_with_status, function ($builder) {
                $builder->where(function ($builder) {
                    $this->search_with_status = ($this->search_with_status == "true") ? true : false;
                    $builder->where('disabled', $this->search_with_status);
                });
            })
            ->paginate($this->perPage);
        // dd($profiles);
        return view('livewire.admin.pakets.profile-list', compact('paketProfiles'))
        ->layout('components.layouts.app', ['title' => trans('system.title.profiles')]);
    }
}
