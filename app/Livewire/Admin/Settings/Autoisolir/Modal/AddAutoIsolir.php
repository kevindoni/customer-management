<?php

namespace App\Livewire\Admin\Settings\Autoisolir\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Models\Customers\AutoIsolir;
use App\Livewire\Actions\AutoIsolirAction;
use App\Services\Mikrotiks\MikrotikService;
use App\Http\Requests\Websystem\AddAutoisolirRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Websystem\EditAutoisolirRequest;

class AddAutoIsolir extends Component
{

    public $addAutoIsolirModal = false;
    public $selectAutoIsolir;
    public $input = [];
    // public $input['selectedAutoIsolirOption'] ='false';
    public $selectedServer = false;
    public $profiles;


    private MikrotikService $mikrotikService;
    public function __construct()
    {
        $this->mikrotikService = new MikrotikService;
    }


    #[On('add-or-edit-autoisolir-modal')]
    public function openAddAutoIsolirModal(AutoIsolir $autoIsolir)
    {
        $this->addAutoIsolirModal = true;
       // if ($autoIsolir->exists) {
            if ($autoIsolir->activation_date) {
                $this->input['selectedAutoIsolirOption'] = 'true';
            } else {
                $this->input['selectedAutoIsolirOption'] = 'false';
            }

            try {
                $this->profiles = $this->mikrotikService->getPppProfiles($autoIsolir->mikrotik);
                $this->selectedServer = true;
            } catch (\Exception $e) {
                $this->selectedServer = false;
            }

            $this->input = array_merge([
                'selectedAutoIsolirOption' =>  $this->input['selectedAutoIsolirOption'],
                'selectedProfile' => $autoIsolir->profile_id,
            ], $autoIsolir->withoutRelations()->toArray());
            $this->selectAutoIsolir = $autoIsolir;
      //  } else {
      //      $this->input['selectedAutoIsolirOption'] = '';
      //      $this->selectAutoIsolir = new AutoIsolir();
      //  }
    }

    /*
    public function addAutoIsolir(AddAutoisolirRequest $request, AutoIsolirAction $autoisolirAction)
    {
        $this->resetErrorBag();
        $request->validate($this->input);

        $response = $autoisolirAction->create(
            $this->input
        );
        if ($response['success']) {
            $this->addAutoIsolirModal = false;
            $this->dispatch('refresh-list-auto-isolir');
            $title = trans('autoisolir.alert.success');
            $message = trans('autoisolir.alert.create-auto-isolir-successfully');
            $this->notification($title, $message, 'success');

        } else {
         $title = trans('autoisolir.alert.failed');
            $message = $response['message'];
            $this->notification($title, $message, 'error');
        }
    }
*/

    public function editAutoIsolir(EditAutoisolirRequest $request, AutoisolirAction $autoisolirAction)
    {
        $this->resetErrorBag();
        $request->validate($this->selectAutoIsolir, $this->input);

        $response = $autoisolirAction->update(
            $this->selectAutoIsolir,
            $this->input
        );


        if ($response['success']) {
            // dd($this->input['selectedAutoIsolirOption'] . ' = ' . $this->selectAutoIsolir->activation_date);
            $this->addAutoIsolirModal = false;
            $this->dispatch('refresh-list-auto-isolir');

            $title = trans('autoisolir.alert.success');
            $message = trans('autoisolir.alert.edit-auto-isolir-successfully');
            $this->notification($title, $message, 'success');
        } else {
            $title = trans('autoisolir.alert.failed');
            $message = $response['message'];
            $this->notification($title, $message, 'error');
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
    /**
     * Get profile from mikrotik after server selected
     */
    public function updatedInputSelectedServer($mikrotik)
    {
        //dd($mikrotik);
        if ($mikrotik != '') {
            // $this->selectedServer = $mikrotik;
            $this->selectedServer = true;
            $mikrotik = Mikrotik::where('id', $mikrotik)->first();
            try {
                $this->profiles = $this->mikrotikService->getPppProfiles($mikrotik);
            } catch (\Exception $e) {
                //  $this->profiles = '';
                $this->selectedServer = false;
            }
        } else {
            $this->selectedServer = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.autoisolir.modal.add-auto-isolir');
    }
}
