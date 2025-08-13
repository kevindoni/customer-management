<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;

class TermOfService extends Component
{
    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.pages.term-of-service');
    }
}
