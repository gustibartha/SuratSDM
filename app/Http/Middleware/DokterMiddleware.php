<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DokterMiddleware
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
        if(Auth::user()->role != 'dokter'){
            alert()->warning('User harus sesuai dengan role nya!!','Warning');
            return redirect(Auth::user()->role);
        }else{
            return $next($request);
        }
    }
}
