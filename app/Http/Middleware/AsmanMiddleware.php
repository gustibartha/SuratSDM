<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AsmanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->role != 'asman'){
            alert()->warning('User harus sesuai dengan role nya!!','Warning');
            return redirect(Auth::user()->role);
        }else{
            return $next($request);
        }
    }
}
