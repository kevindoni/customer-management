<?php

namespace App\Traits;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


trait NotificationTrait
{
    public function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    public function success_notification($title, $message)
    {
        LivewireAlert::title($title)
        ->text($message)
        ->position('top-end')
        ->toast()
        ->success()
        ->show();
    }

    public function error_notification($title, $message)
    {
        LivewireAlert::title($title)
        ->text($message)
        ->position('top-end')
        ->toast()
        ->error()
        ->show();
    }

    public function warning_notification($title, $message)
    {
        LivewireAlert::title($title)
        ->text($message)
        ->position('top-end')
        ->toast()
        ->warning()
        ->show();
    }

    public function info_notification($title, $message)
    {
        LivewireAlert::title($title)
        ->text($message)
        ->position('top-end')
        ->toast()
        ->info()
        ->show();
    }
}
