<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Address extends Component
{
    public $user;
    public function mount(): void
    {
        $this->user = Auth::user();
    }
}
