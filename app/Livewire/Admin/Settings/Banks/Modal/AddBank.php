<?php

namespace App\Livewire\Admin\Settings\Banks\Modal;

use App\Models\Bank;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AddBank extends Component
{

    public $addBankModal = false;
    public $input = [];

    public $bank;

    #[On('show-add-bank-modal')]
    public function showAddBankModal(Bank $bank)
    {

        $this->addBankModal = true;
        if ($bank->exists) {
            $this->input = array_merge([
                //
            ], $bank->withoutRelations()->toArray());
            $this->bank = $bank;
        } else {
            $this->bank = new Bank();
        }
    }

    public function addBank()
    {
        $this->input['slug'] = Str::slug($this->input['bank_name'], '-') . Str::random(4);
        Bank::create($this->input);
        $message = trans('bank.notification.add-bank-successfully');
        $this->closeModal($message);
    }

    public function updateBank()
    {
        $this->bank->forceFill($this->input)->save();
        $message = trans('bank.notification.update-bank-successfully');
        $this->closeModal($message);
    }

    public function closeModal($message)
    {
        $this->notification('Success', $message, 'success');
        $this->dispatch('refresh-bank-list');
        $this->addBankModal = false;
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
        return view('livewire.admin.settings.banks.modal.add-bank');
    }
}
