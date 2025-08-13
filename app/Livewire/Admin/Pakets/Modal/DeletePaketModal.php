<?php

namespace App\Livewire\Admin\Pakets\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Pakets\Paket;
use App\Services\PaketService;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\DB;
use App\Livewire\Actions\Pakets\PaketAction;
use App\Http\Requests\CurrentPasswordRequest;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Http\Requests\Paket\DeletePaketRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeletePaketModal extends Component
{
    use NotificationTrait;
    public $deletePaketModal = false;
    public $paketSelect = null;
    public $input = [];
    // public $contentCheckbox;

    /**
     * Delete paketk function
     * Show delete confirmation modal
     */
    #[On('show-delete-paket-modal')]
    public function showDeleteModal(Paket $paket)
    {
        $this->input['showForceDeleteButton'] = false;
        $this->paketSelect = $paket;
        $this->deletePaketModal = true;
    }

    /**
     * Deleted paket after validate user
     */
    public function deletePaket(DeletePaketRequest $request, MikrotikPppService $mikrotikPppService)
    {
        $this->resetErrorBag();
        $request->validate($this->input, $this->paketSelect);
        $paketName = $this->paketSelect->name;

        DB::beginTransaction();
        try {
            foreach ($this->paketSelect->customer_pakets as $customer_paket) {
                $mikrotik = $customer_paket->paket->mikrotik;
                $selectedIdPaket = $this->input['selectedPaket'];
                if ($selectedIdPaket != $this->paketSelect->id) {
                    //Move customer paket to other profile
                    if ($customer_paket->isPpp()) {
                        $profileName = $customer_paket->paket->paket_profile->profile_name;
                        $mikrotikPppService->updateProfileSecret($mikrotik, $customer_paket->customer_ppp_paket, $profileName);
                    } else if ($customer_paket->isIpStatic()) {
                        //
                    }

                    //update customer paket to selected paket
                    $customer_paket->update([
                        'paket_id' => $selectedIdPaket
                    ]);
                } else {
                    //Deleted customer paket in this paket
                    if ($customer_paket->isPpp()) {
                        $mikrotikPppService->disableSecret($mikrotik, $customer_paket->customer_ppp_paket, 'true');
                        $customer_paket->customer_ppp_paket->delete();
                    } else if ($customer_paket->isIpStatic()) {
                        $customer_paket->customer_static_paket->delete();
                    }
                    $customer_paket->delete();
                }
            }

            //Delete paket
            $this->paketSelect->delete();
            DB::commit();
            $title =  trans('paket.alert.delete-paket', ['paket' => $paketName]);
            $message = trans('paket.alert.delete-paket-successfully', ['paket' => $paketName]);
            $this->notification($title, $message, 'success');
            $this->closeDeletePaketModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->input['showForceDeleteButton'] = true;
            $title =  trans('paket.alert.failed');
            $message = $e->getMessage();
            $this->notification($title, $message, 'error');
        }
    }

    public function forceDeletePaket(DeletePaketRequest $request)
    {
        $this->resetErrorBag();
        $request->validate($this->input, $this->paketSelect);
        $paketName = $this->paketSelect->name;

        DB::beginTransaction();
        try {
            //Delete paket
            $this->paketSelect->delete();
            DB::commit();
            $title =  trans('paket.alert.delete-paket', ['paket' => $paketName]);
            $message = trans('paket.alert.delete-paket-successfully', ['paket' => $paketName]);
            $this->notification($title, $message, 'success');
            $this->closeDeletePaketModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $title =  trans('paket.alert.failed');
            $message = $e->getMessage();
            $this->notification($title, $message, 'error');
        }
    }


    /**
     * Clode confirmation modal
     */
    public function closeDeletePaketModal()
    {
        //$this->mikrotik = '';
        $this->dispatch('refresh-paket-list');
        $this->deletePaketModal = false;

        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.pakets.modal.delete-paket-modal');
    }
}
