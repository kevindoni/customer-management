<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ToogleButtonEnable extends Component
{

    public Model $model;
    public string $field;
    public string $dispatch;

    public bool $hasDisable;

    //dispatch
    public $alert_title, $alert_message;


    //#[On('updated')]
    public function mount()
    {
        $this->hasDisable = (bool) $this->model->getAttribute($this->field);
        // if ($disable) {
        //    $this->hasDisable = false;
        //$this->status = trans('system.disable');
        // } else {
        //     $this->hasDisable = true;
        //  $this->status = trans('system.enable');
        //}
    }


    public function render()
    {
        return view('livewire.components.toogle-button-enable');
    }
    public function updating($field, $value)
    {
        //  if ($value) {
        //      $value = false;
        //  } else {
        //      $value = true;
        // }
        $this->model->setAttribute($this->field, $value)->save();
        $this->dispatch($this->dispatch, model: $this->model->slug);
        // return dd($this->model->slug);
        // $this->alert_title;
        // $this->alert_message =  $this->alert_message;
        // $this->dispatch($this->dispatch, $this->alert_message);
    }
}
