<?php

namespace App\Livewire\Admin\Mikrotiks\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikService;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mikrotik\AddMikrotikRequest;
use App\Livewire\Actions\Mikrotiks\MikrotikAction;
use App\Http\Requests\Mikrotik\UpdateMikrotikRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Http\Requests\Mikrotik\AddMikrotikStep1Request;
use App\Http\Requests\Mikrotik\UpdateMikrotikStep1Request;

class AddMikrotik extends Component
{
    public $addMikrotikModal = false;
    public $input = [];
    public $mikrotik;
    public $supportRestApi = false;
    public $withoutTest = false;
    public $currentStep = 1;

    /**
     * Add or Edit Mikrotik Modal
     */
    //public $profiles;
    #[On('show-add-mikrotik-modal')]
    public function showAddPaketModal(Mikrotik $mikrotik)
    {
        $this->reset();
        $this->addMikrotikModal = true;

        if ($mikrotik->exists) {
            $this->mikrotik = $mikrotik;
            $this->input = array_merge([
                'name' => $mikrotik->name
            ], $mikrotik->withoutRelations()->toArray());
            $this->input['trying_rest_api']  = false;
            $this->input['add_without_test']  = false;
            //$this->input['username'] = $this->input['password'] = '';
            //$this->input['version'] = '';
           // unset($this->input['version']);
            $this->input['use_ssl'] = $mikrotik->use_ssl ? true : false;
        } else {
            $this->input['port'] = '';
            $this->input['use_ssl'] = false;
            $this->input['web_port'] = '';
            $this->input['trying_rest_api']  = false;
            $this->input['add_without_test']  = false;
            $this->mikrotik = new Mikrotik();
        }
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function addFirstStepSubmit(AddMikrotikStep1Request $addMikrotikStep1Request)
    {
        $addMikrotikStep1Request->validate($this->input);
        $this->input['web_port'] =  $this->input['web_port'] ? $this->input['web_port'] : ($this->input['use_ssl'] ? 443 : 80);
        /*
        if ($this->input['trying_rest_api']) {
            try {
                $mikrotik =  $mikrotikService->testConnectionWthRestApi($this->input);
                $this->input['mikrotik'] = array_merge($mikrotik['result']);
                $this->currentStep = 2;
            } catch (\Exception $e) {
                $this->withoutTest = true;
                $this->supportRestApi = false;
                $this->input['trying_rest_api'] = false;
                $title = trans('mikrotik.alert.failed');
                $message = trans('mikrotik.alert.failed') . ' ⇌ ' . trans('mikrotik.alert.failed-to-connect', ['host' => $this->input['host'], 'message' => $e->getMessage()]);
                $this->notification($title, $message, 'error');
            }
        } else if ($this->input['add_without_test']) {

            $mikrotik =  [
                'platform' => $this->input['platform'] ?? null,
                'version' => $this->input['version'] . '.' . $this->input['subVersion'],
                'board-name' => $this->input['boardname'] ?? null,
            ];
            $this->input['mikrotik'] = array_merge($mikrotik);
            $this->currentStep = 2;
        } else {
            try {
                $mikrotik =  $mikrotikService->testConnection($this->input);
                if (version_compare($mikrotik['version'], '7.9.0', '>=')) {
                    $mikrotik =  $mikrotikService->testConnectionWthRestApi($this->input);
                }
                $this->input['mikrotik'] = array_merge($mikrotik['result']);
                $this->currentStep = 2;
            } catch (\Exception $e) {
                $this->supportRestApi = true;
                $title = trans('mikrotik.alert.failed');
                $message = trans('mikrotik.alert.failed') . ' ⇌ ' . trans('mikrotik.alert.failed-to-connect', ['host' => $this->input['host'], 'message' => $e->getMessage()]);
                $this->notification($title, $message, 'error');
            }
        }*/
        $this->connectionTest();
    }

    public function addMikrotik(AddMikrotikRequest $addMikrotikRequest, MikrotikAction $mikrotikAction)
    {
        $addMikrotikRequest->validate(
            $this->input
        );
        $this->resetErrorBag();
        $response = $mikrotikAction->store(
            $this->input
        );

        if ($response['status'] == 'success') {
            $this->closeModal();
        }
        $this->notification($response['title'], $response['message'], $response['status']);
    }

    public function updateFirstStepSubmit(UpdateMikrotikStep1Request $updateMikrotikStep1Request, MikrotikService $mikrotikService)
    {
        $updateMikrotikStep1Request->validate($this->mikrotik, $this->input);
        $this->input['web_port'] =  $this->input['web_port'] ? $this->input['web_port'] : ($this->input['use_ssl'] ? 443 : 80);
        if ($this->mikrotik->host != $this->input['host'] || $this->mikrotik->port != $this->input['port'] || $this->mikrotik->username != $this->input['username'] || $this->mikrotik->password != $this->input['password'] || $this->mikrotik->use_ssl != $this->input['use_ssl'] || $this->mikrotik->web_port != $this->input['web_port']){
            $this->connectionTest();
        } else {

            $version = $this->input['add_without_test'] ? $this->input['version'] . $this->input['subVersion1'] . $this->input['subVersion2'] : $this->mikrotik->version;
            $mikrotik =  [
                'platform' => $this->input['platform'] ?? $this->mikrotik->merk_router,
                'version' => $version,
                'board-name' => $this->input['boardname'] ?? $this->mikrotik->type_router,
            ];
            $this->input['mikrotik'] = array_merge($mikrotik);
            $this->currentStep = 2;
        }
    }

    public function updateMikrotik(UpdateMikrotikRequest $request)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
        (new MikrotikAction())->update($this->mikrotik, $this->input);

        $title = trans('mikrotik.alert.connected');
        $message = trans('mikrotik.alert.mikrotik-updated', ['mikrotik' =>  $this->mikrotik->name]);
        $this->closeModal();
        $this->notification($title, $message, 'success');
    }

    private function connectionTest()
    {
        if ($this->input['trying_rest_api']) {
            try {
                $mikrotik =  (new MikrotikService())->testConnectionWthRestApi($this->input);
                $this->input['mikrotik'] = array_merge($mikrotik['result']);
                $this->currentStep = 2;
            } catch (\Exception $e) {
                $this->withoutTest = true;
                $this->supportRestApi = false;
                $this->input['trying_rest_api'] = false;
                $title = trans('mikrotik.alert.failed');
                $message = trans('mikrotik.alert.failed') . ' ⇌ ' . trans('mikrotik.alert.failed-to-connect', ['host' => $this->input['host'], 'message' => $e->getMessage()]);
                $this->notification($title, $message, 'error');
            }
        } else if ($this->input['add_without_test']) {

            $mikrotik =  [
                'platform' => $this->input['platform'] ?? null,
                'version' => $this->input['version'] . $this->input['subVersion1'] . $this->input['subVersion2'],
                'board-name' => $this->input['boardname'] ?? null,
            ];
            $this->input['mikrotik'] = array_merge($mikrotik);
            $this->currentStep = 2;
        } else {
            try {
                $mikrotik =  (new MikrotikService())->testConnection($this->input);

                if (version_compare($mikrotik['result']['version'], '7.9.0', '>=')) {
                    $mikrotik =  (new MikrotikService())->testConnectionWthRestApi($this->input);
                }
                $this->input['mikrotik'] = array_merge($mikrotik['result']);
                $this->currentStep = 2;
            } catch (\Exception $e) {
                $this->supportRestApi = true;
                $title = trans('mikrotik.alert.failed');
                $message = trans('mikrotik.alert.failed') . ' ⇌ ' . trans('mikrotik.alert.failed-to-connect', ['host' => $this->input['host'], 'message' => $e->getMessage()]);
                $this->notification($title, $message, 'error');
            }
        }
    }
    private function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
            ->text($message)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }
    public function closeModal()
    {
        $this->addMikrotikModal = false;
        $this->dispatch('refresh-mikrotik-list');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.admin.mikrotiks.modal.add-mikrotik');
    }
}
