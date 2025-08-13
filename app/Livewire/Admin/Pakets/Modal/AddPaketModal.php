<?php

namespace App\Livewire\Admin\Pakets\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use App\Models\Pakets\PaketProfile;
use App\Livewire\Actions\Pakets\PaketAction;
use App\Http\Requests\Paket\AddPaketStep1Request;
use App\Http\Requests\Paket\AddPaketStep2Request;
use App\Http\Requests\Paket\EditPaketStep1Request;
use App\Http\Requests\Paket\EditPaketStep2Request;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AddPaketModal extends Component
{
    public $addPaketModal = false;
    public $input = [];
    public $profiles;
    public $paketSelect = null;
    public $selectedServer = null;
    public $currentStep = 1;

    /**
     * Add or Edit Paket Modal
     */
    //public $profiles;
    #[On('show-add-paket-modal')]
    public function showAddPaketModal(Paket $paket)
    {
        $this->addPaketModal = true;
        if ($paket->exists) {
            //  dd($paket);
            $this->profiles = PaketProfile::whereDisabled('false')->orderBy('profile_name')->get();
            $this->input = array_merge([
                'selectedProfile' => $paket->paket_profile_id,
              //  'checkbox_show_on_customer' => $paket->show_on_customer ? true : false
            ], $paket->withoutRelations()->toArray());
            $this->paketSelect = $paket;
            $this->selectedServer = $paket->mikrotik_id;
            // $this->profiles = $this->getProfileServer($paket->mikrotik_id);
        } else {
            $this->paketSelect = new Paket();
        }
    }

    public function addFirstStepSubmit(AddPaketStep1Request $request, EditPaketStep1Request $editRequest)
    {
        if ( $this->paketSelect->id){
            $editRequest->validate($this->paketSelect, $this->input);
        } else {
            $request->validate($this->input);
        }

        $this->currentStep = 2;
    }
    /**
     * Add Paket
     */
    public function addPaket(AddPaketStep2Request $request, PaketAction $paketAction)
    {
        $this->resetErrorBag();
        $status = $paketAction->add_paket(
            $request->validate(
                $this->input
            )
        );
        if ($status['success']) {
            $this->notification(trans('paket.alert.add-paket'),trans('paket.alert.add-paket-successfully', ['paket' => $this->input['name']]),'success');

        } else {
            $this->notification(trans('paket.alert.failed'),$status['message'],'error');
        }

        $this->closeAddPaketModal();
    }


    /**
     * Update paket
     */
    public function updatePaket(EditPaketStep2Request $request, PaketAction $paketAction)
    {
        $this->resetErrorBag();
        $request->validate($this->paketSelect, $this->input);
        $status = $paketAction->update_paket(
            $this->paketSelect,
            $this->input
        );

        if ($status['success']) {
            $this->notification(trans('paket.alert.update-paket'),trans('paket.alert.update-paket-successfully', ['paket' => $this->paketSelect->name]),'success');

        } else {
            $this->notification(trans('paket.alert.failed'),$status['message'],'error');
        }



        $this->closeAddPaketModal();
    }

    /**
     * Close add paket modal
     */
    public function closeAddPaketModal()
    {
        // $this->dispatch('refresh-navigation-menu');
        // $this->dispatch('refresh-navigation-paket-menu');
        $this->dispatch('refresh-paket-list');
        $this->addPaketModal = false;
        $this->reset();
    }


    public function back($step)
    {
        $this->currentStep = $step;
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
    public function render()
    {
        return view('livewire.admin.pakets.modal.add-paket-modal');
    }
}
