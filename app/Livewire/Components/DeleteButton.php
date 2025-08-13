<?php

namespace App\Livewire\Components;

use App\Actions\Fortify\ValidateUserPassword;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class DeleteButton extends Component
{
    public Model $model;
    public $state = [];
    public string $dispatch;
    public $deleteModal = false;

    public function render()
    {
        return view('livewire.components.delete-button');
    }


    public function showDeleteModal()
    {
        $this->deleteModal = true;
    }


    public function delete(ValidateUserPassword $validation)
    {
        $this->resetErrorBag();
        $validation->user_password(
            $this->state
        );
        $this->model->delete();
        $this->deleteModal = false;
        $this->dispatch($this->dispatch, model: $this->model->slug);
    }
}
