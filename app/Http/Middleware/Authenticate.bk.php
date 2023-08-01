<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty(session('authenticated'))) {
            $request->session()->put('authenticated', time());
            return $next($request);
        }
       
        // return redirect(ENV('APP_URL').'/login');
        return redirect(ENV('APP_URL').'/login?redirect-to='.$request->getPathInfo());
    }
}
