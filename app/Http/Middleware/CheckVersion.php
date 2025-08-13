<?php

namespace App\Http\Middleware;

use App\Livewire\Update;
use Closure;
use Illuminate\Http\Request;

class CheckVersion
{
   // protected const NEW_VERSION = '2.0.1';
    public function handle(Request $request, Closure $next)
    {
        if (version_compare(env('APP_VERSION'), Update::NEW_VERSION, '<')) {
            return redirect()->route('update');
        }
        return $next($request);
    }
}
