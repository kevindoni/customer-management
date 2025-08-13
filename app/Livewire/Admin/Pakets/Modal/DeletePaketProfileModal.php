<?php

namespace App\Livewire\Admin\Pakets\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\PaketProfile;
use App\Livewire\Actions\Pakets\PaketProfileAction;
use App\Http\Requests\Paket\DeletePaketRequest;
use App\Http\Requests\Paket\DeletePaketProfileRequest;

class DeletePaketProfileModal extends Component
{
    public $deletePaketProfileModal = false;
    public $paketProfileSelect = null;
    public $input = [];
    public $contentCheckbox;

    /**
     * Delete paketk function
     * Show delete confirmation modal
     */
    #[On('show-delete-paket-profile-modal')]
    public function showDeleteModal(PaketProfile $paketProfile)
    {
        $this->paketProfileSelect = $paketProfile;
        $this->input['checkbox_permanent_delete'] = false;
        $this->input['checkbox_delete_on_mikrotik'] = true;
        $this->deletePaketProfileModal = true;
    }

    /**
     * Deleted paket after validate user
     */
    public function deletePaketProfile(DeletePaketProfileRequest $request)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
        $paketProfileName = $this->paketProfileSelect->profile_name;

        $response = (new PaketProfileAction())->delete_profile($this->paketProfileSelect, $this->input);

        if ($response == 'success') {
            $this->dispatch('notify', [
                'status' => 'warning',
                'title' => trans('paket.alert.delete-paket-profile'),
                'message' =>  trans('paket.alert.delete-paket-profile-successfully', ['profile' => $paketProfileName])
            ]);
            $this->closeDeletePaketProfileModal();
        } else {
            $this->dispatch('notify', [
                'status' => 'error',
                'title' => trans('paket.alert.failed'),
                'message' => $response
            ]);
        }
    }


    /**
     * Clode confirmation modal
     */
    public function closeDeletePaketProfileModal()
    {
        //$this->mikrotik = '';
        $this->dispatch('refresh-paket-profile');
        $this->deletePaketProfileModal = false;

        $this->reset();
    }


    public function render()
    {
        return view('livewire.admin.pakets.modal.delete-paket-profile-modal');
    }
}
