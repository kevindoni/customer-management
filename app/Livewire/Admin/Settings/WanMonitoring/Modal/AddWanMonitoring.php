<?php

namespace App\Livewire\Admin\Settings\WanMonitoring\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Servers\Mikrotik;
use App\Models\Servers\MikrotikMonitoring;
use App\Services\Mikrotiks\MikrotikService;
use App\Http\Resources\Mikrotik\InterfaceResource;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Livewire\Actions\Mikrotiks\MikrotikMonitoringAction;
use App\Http\Requests\Websystem\AddMikrotikMonitoringRequest;
use App\Http\Requests\Websystem\EditMikrotikMonitoringRequest;

class AddWanMonitoring extends Component
{
    public $addWanMonitoringModal = false;
    public MikrotikMonitoring $mikrotikMonitoring;
    public $input = [];
    public $mikrotik_interfaces;

    #[On('show-add-mikrotik-monitoring-modal')]
    public function showAddMikrotikMonitoringModal(MikrotikMonitoring $mikrotikMonitoring)
    {
        $this->addWanMonitoringModal = true;
        if ($mikrotikMonitoring->exists) {
            try {
                $this->mikrotik_interfaces = (new MikrotikService())->mikrotikEtherInterface($mikrotikMonitoring->mikrotik);
            } catch (\Exception $e) {
                $this->mikrotik_interfaces = null;
            }
            $this->mikrotikMonitoring = $mikrotikMonitoring;
            $this->input = array_merge([
                'selectedServer' => $mikrotikMonitoring->mikrotik->name,

            ], $mikrotikMonitoring->withoutRelations()->toArray());
            $this->input['username'] = $this->input['password'] = '';
        } else {
            $this->mikrotikMonitoring = new MikrotikMonitoring();
        }
    }

    public function addMikrotikMonitoring(AddMikrotikMonitoringRequest $request)
    {
        $this->resetErrorBag();
        $request->validate(
            $this->input
        );

        $interfaces = InterfaceResource::collection($this->mikrotik_interfaces);
        $interface = $interfaces->where('name', $this->input['interface'])->first();

        $this->input['interface'] = $interface['name'];
        $this->input['interface_type'] = $interface['type'];

        $this->mikrotikMonitoring = (new MikrotikMonitoringAction())->add_monitoring($this->input);

        $title = trans('autoisolir.alert.success');
        $message = trans('mikrotik.alert.add-mikrotik-monitoring-successfully');
        $this->notification($title, $message, 'success');

        $this->closeModal();
    }


    public function updateMikrotikMonitoring(EditMikrotikMonitoringRequest $request)
    {
        $this->resetErrorBag();
        $request->validate($this->input);
        $interfaces = InterfaceResource::collection($this->mikrotik_interfaces);
        $interface = $interfaces->where('name', $this->input['interface'])->first();

        $this->input['interface'] = $interface['name'];
        $this->input['interface_type'] = $interface['type'];
        (new MikrotikMonitoringAction())->update_monitoring($this->mikrotikMonitoring, $this->input);

        $title = trans('autoisolir.alert.success');
        $message = trans('mikrotik.alert.update-mikrotik-monitoring-successfully');
        $this->notification($title, $message, 'success');

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->addWanMonitoringModal = false;
        $this->dispatch('refresh-mikrotik-monitoring-list', mikrotikMonitoring: $this->mikrotikMonitoring->slug);
    }


    public function updatedInputSelectedServer($mikrotik_id)
    {
        $mikrotik = Mikrotik::find($mikrotik_id);
        try {
            $this->mikrotik_interfaces = (new MikrotikService())->mikrotikEtherInterface($mikrotik);
        } catch (\Exception $e) {
            $this->mikrotik_interfaces = null;
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

    public function render()
    {

        return view('livewire.admin.settings.wan-monitoring.modal.add-wan-monitoring');
    }
}
