<?php

namespace App\Livewire\Admin\Pakets\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\PaketProfile;
use App\Http\Requests\Paket\AddPaketProfileRequest;
use App\Livewire\Actions\Pakets\PaketProfileAction;
use App\Http\Requests\Paket\EditPaketProfileRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AddPaketProfileModal extends Component
{
    public $addPaketProfileModal = false;
    public $paketProfileSelect;
    public $input = [];


    #[On('show-add-paket-profile-modal')]
    public function showAddPaketProfileModal(PaketProfile $paketProfile)
    {
        $this->reset();
        $this->addPaketProfileModal = true;

        if ($paketProfile->exists) {
            $rate_limit = explode(' ', $paketProfile->rate_limit);
            $this->paketProfileSelect = $paketProfile;
            if (count($rate_limit) == 6) {
                $this->input = array_merge([
                    'profile_name' => $paketProfile->profile_name,
                    'max_limit' => $rate_limit[0],
                    'burst_limit' => $rate_limit[1],
                    'burst_threshold' => $rate_limit[2],
                    'burst_time' => $rate_limit[3],
                    'priority' => $rate_limit[4],
                    'limit_at' => $rate_limit[5],
                ], $paketProfile->withoutRelations()->toArray());
            } else {
                $this->input = array_merge([
                    'profile_name' => $paketProfile->profile_name,
                ], $paketProfile->withoutRelations()->toArray());
            }
        } else {
            $this->paketProfileSelect = new PaketProfile();
        }
    }

    public function addPaketProfile(AddPaketProfileRequest $request)
    {
        $request->validate($this->input);
        $profile = (new PaketProfileAction())->add_profile($this->input);
        $this->notification(trans('paket.alert.success'), trans('paket.alert.add-profile-paket-successfully', ['profile' => $profile->profile_name]),'success');

        $this->closeAddPaketProfileModal();
    }

    public function updatePaketProfile(EditPaketProfileRequest $request)
    {
        $request->validate($this->paketProfileSelect, $this->input);
        $profile = (new PaketProfileAction())->update_profile($this->paketProfileSelect, $this->input);
        if ($profile['status'] == 'success') {
            $this->notification(trans('paket.alert.success'), trans('paket.alert.update-profile-paket-successfully', ['profile' => $this->paketProfileSelect->profile_name]),'success');
            $this->closeAddPaketProfileModal();
        } else {
            $this->notification(trans('paket.alert.failed'), $profile['message'],'error');

        }
    }

    public function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
        ->text($message)
        ->position('top-end')
        ->toast()
        ->status($status)
        ->show();
    }
    public function closeAddPaketProfileModal()
    {
        $this->dispatch('refresh-paket-profile');
        $this->addPaketProfileModal = false;
    }
    public function render()
    {
        return view('livewire.admin.pakets.modal.add-paket-profile-modal');
    }
}
