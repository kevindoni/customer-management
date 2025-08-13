<?php

namespace App\Livewire\Admin\Settings\General;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Log;
use App\Models\Websystem;

class QueueSessionForm extends Component
{
    public $input = [];


    public function mount()
    {
       // dd(env('APP_ENV'));
        $websystem = Websystem::first();
        $this->input['app_env'] = env('APP_ENV');
        $this->input['app_debug'] = env('APP_DEBUG');
        $this->input['session_driver'] = env('SESSION_DRIVER');
        $this->input['cache_store'] = env('CACHE_STORE');
        $this->input['queue_connection'] = env('QUEUE_CONNECTION');
        $this->input['app_timezone'] = env('APP_TIMEZONE');
        $this->input['isolir_driver'] = $websystem->isolir_driver;
        $this->input['subscription_type'] = $websystem->subscription_mode;

    }

    public function update_env()
    {
        Validator::make($this->input, [
            'app_timezone' => ['required'],
        ])->validate();

        setEnv('APP_ENV', $this->input['app_env']);
        setEnv('APP_DEBUG', $this->input['app_debug'] ? 'true':'false');
        setEnv('SESSION_DRIVER', $this->input['session_driver']);
        setEnv('CACHE_STORE',  $this->input['cache_store']);
        setEnv('QUEUE_CONNECTION', $this->input['queue_connection']);
        setEnv('APP_TIMEZONE',  $this->input['app_timezone']);
      //  setEnv('ISOLIR_DRIVER',  $this->input['isolir_driver']);
       // setEnv('SUBSCRIPTION_TYPE',  $this->input['subscription_type']);
       $websystem = Websystem::first();
       $websystem->forceFill([
           'isolir_driver' => $this->input['isolir_driver'],
           'subscription_mode' =>$this->input['subscription_type']
        ])->save();

        LivewireAlert::title(trans('websystem.alert.updated'))
            ->text(trans('websystem.alert.updated-message-successfully'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
        Artisan::call('optimize:clear');

    }

    public function link_storage()
    {
        $status = Artisan::call('storage:link');
        Log::info($status);
        LivewireAlert::title(trans('websystem.alert.success'))
            ->text(trans('websystem.alert.link-storage-success'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();
    }

    public function optimize()
    {
        Artisan::call('optimize:clear');
        LivewireAlert::title(trans('websystem.alert.success'))
            ->text(trans('websystem.alert.optimize-success'))
            ->position('top-end')
            ->toast()
            ->status('success')
            ->show();

    }
    public function render()
    {
        return view('livewire.admin.settings.general.queue-session-form');
    }
}
