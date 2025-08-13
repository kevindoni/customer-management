<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\ScriptService;
use App\Livewire\Actions\AutoIsolirAction;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use App\Models\Customers\AutoIsolir as ModelsAutoIsolir;

class AutoIsolir extends Component
{
    use WithPagination;
    public ModelsAutoIsolir $autoisolir;

    // Pagination
    public $perPage = 25;

    // Order by
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    private ScriptService $scriptService;
    public function __construct()
    {
        // Initialize
        $this->scriptService = new ScriptService;
    }

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

    // public $buttonReset = false;
    //  #[On('autoisolir-update-save')]
    public function reset_auto_isolir(ModelsAutoIsolir $autoIsolir, AutoisolirAction $autoisolirAction)
    {
        try {
            $resetScriptOnMikrotik = $autoisolirAction->reset_script_mikrotik($autoIsolir);

            // if ($resetScriptOnMikrotik['status'] == 'error') {
            //    $this->buttonReset = true;
            //} else {
            //     $this->buttonReset = false;
            // }

            $this->notification($resetScriptOnMikrotik['title'], $resetScriptOnMikrotik['message'], $resetScriptOnMikrotik['status']);
        } catch (\Exception $e) {
            $this->notification(trans('autoisolir.alert.failed'), $e->getMessage(), 'error');
        }
    }

    public function activation_auto_isolir_mikrotik(ModelsAutoIsolir $autoIsolir, AutoisolirAction $autoisolirAction)
    {
        try {
            $createScriptOnMikrotik = $autoisolirAction->add_script_to_mikrotik($autoIsolir);
            $this->notification($createScriptOnMikrotik['title'], $createScriptOnMikrotik['message'], $createScriptOnMikrotik['status']);
        } catch (\Exception $e) {
            $this->notification(trans('autoisolir.alert.failed'), $e->getMessage(), 'error');
        }
    }


    /**
     * Alert when paket successfully disable or enable
     */
    #[On('autoisolir-disable')]
    public function alert($model)
    {
        $model = ModelsAutoIsolir::find($model['id']);
        $mikrotik = Mikrotik::where('id', $model->mikrotik_id)->first();
        $autoIsolir = $mikrotik->auto_isolir;
        $webSystem = Websystem::first();

        if ($model->disabled) {
            try {
                if ($webSystem->isolir_driver === 'mikrotik') $this->scriptService->disabledScheduleById($mikrotik, $autoIsolir->schedule_id, 'true');
                $status = 'warning';
                $alert_title = trans('autoisolir.alert.disable');
                $alert_message = trans('autoisolir.alert.disable-message-successfully', ['name' => $model->mikrotik->name]);
            } catch (\Exception $e) {
                $model->update([
                    'disabled' => false
                ]);

                $status = 'error';
                $alert_title =  trans('autoisolir.alert.failed-enable');
                $alert_message = $e->getMessage();
            }
        } else {
            try {
                if ($webSystem->isolir_driver === 'mikrotik') $this->scriptService->disabledScheduleById($mikrotik, $autoIsolir->schedule_id, 'false');
                $status = 'success';
                $alert_title = trans('autoisolir.alert.enable');
                $alert_message = trans('autoisolir.alert.enable-message-successfully', ['name' => $model->mikrotik->name]);
            } catch (\Exception $e) {
                $model->update([
                    'disabled' => true
                ]);

                $status = 'error';
                $alert_title =  trans('autoisolir.alert.failed-enable');
                $alert_message = $e->getMessage();
            }
        }
        $this->notification($alert_title, $alert_message, $status);
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

    #[On('refresh-list-auto-isolir')]
    public function render()
    {
        $websystem = Websystem::first();
        $autoIsolirs = ModelsAutoIsolir::orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.admin.settings.auto-isolir', [
            'autoisolirs' => $autoIsolirs,
            'websystem' => $websystem
        ]);
    }
}
