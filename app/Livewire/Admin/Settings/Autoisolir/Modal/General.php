<?php

namespace App\Livewire\Admin\Settings\Autoisolir\Modal;


use Livewire\Component;
use App\Models\Websystem;
use Livewire\Attributes\On;
use App\Models\Customers\AutoIsolir;
use Illuminate\Support\Facades\Validator;
use App\Livewire\Actions\AutoIsolirAction;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class General extends Component
{
    public $generalAutoIsolirModal = false;
    public $websystem;
    public $input = [];

    #[On('edit-general-autoisolir-modal')]
    public function openAddAutoIsolirModal()
    {
        $this->websystem = Websystem::first();
        $this->generalAutoIsolirModal = true;
        $this->input = array_merge([
            'comment_payment' => $this->websystem->comment_payment,
            'comment_unpayment' => $this->websystem->comment_unpayment,
            'auto_isolir_driver' => $this->websystem->isolir_driver,
        ], $this->websystem->withoutRelations()->toArray());
    }

    public function edit_general()
    {
        Validator::make($this->input, [
            'comment_payment' => ['required_if:auto_isolir_driver,==,mikrotik'],
            'comment_unpayment' => ['required_if:auto_isolir_driver,==,mikrotik'],
        ])->validate();

        $this->websystem->forceFill([
            'comment_payment' => $this->input['comment_payment'],
            'comment_unpayment' => $this->input['comment_unpayment'],
            'isolir_driver' => $this->input['auto_isolir_driver']
        ])->save();

        // if ( Websystem::first()->isolir_driver == 'mikrotik')
        if ($this->input['auto_isolir_driver'] == 'cronjob') {
            $autoIsolirs = AutoIsolir::all();
            foreach ($autoIsolirs as $autoIsolir) {
                if ($autoIsolir->script_id != 0 && $autoIsolir->schedule_id != 0) {
                    try {
                        (new AutoIsolirAction())->remove_script_isolir_from_mikrotik($autoIsolir);
                    } catch (\Exception $e) {
                        $this->notification('Failed', $e->getMessage(), 'error');
                    }
                }
            }
        }
        $this->dispatch('refresh-list-auto-isolir');
        $title = trans('autoisolir.alert.success');
        $message = trans('autoisolir.alert.update-auto-isolir-successfully');
        $this->notification($title, $message, 'success');
        $this->generalAutoIsolirModal = false;
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
        return view('livewire.admin.settings.autoisolir.modal.general');
    }
}
