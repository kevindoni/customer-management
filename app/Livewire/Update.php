<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Artisan;

class Update extends Component
{
    public const NEW_VERSION = '2.0.6';
    #[Layout('components.layouts.install')]
    public function render()
    {
        if (version_compare(env('APP_VERSION'), self::NEW_VERSION, '>=')) {
            $this->redirectRoute('home');
        }
        return view('livewire.update');
    }

    public function update()
    {
        try {
            Artisan::call('migrate');
            setEnv('APP_VERSION', self::NEW_VERSION);
            setEnv('APP_INSTALLED', true);
			Artisan::call('optimize:clear');
            $this->redirectRoute('home');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
