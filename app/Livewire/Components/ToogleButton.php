<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ToogleButton extends Component
{

    public Model $model;
    public string $field;
    public string $dispatch;

    public bool $hasDisable;

    public bool $disableButton = false;

    //dispatch
    public $alert_title, $alert_message;

    //#[On('updated')]
    public function mount()
    {
        $disable = (bool) $this->model->getAttribute($this->field);
        if ($disable) {
            $this->hasDisable = false;
        } else {
            $this->hasDisable = true;
        }
    }

    public function render()
    {
        return view('livewire.components.toogle-button');
    }
    public function updating($field, $value)
    {
       // dd($this->field);
        if ($value) {
            $value = false;
        } else {
            $value = true;
        }
        $this->model->setAttribute($this->field, $value)->save();
        // $this->dispatch($this->dispatch, model: $this->model->slug);
        $this->dispatch($this->dispatch, model: $this->model);
    }
}
