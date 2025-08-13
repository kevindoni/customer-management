<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (env('APP_INSTALLED') === false){
            return redirect()->route('install');
        }
        return $next($request);
    }
}
