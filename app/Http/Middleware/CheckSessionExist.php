<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;

class CheckSessionExist
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
        // return $next($request);
        // session_start();
        // if (!$_SESSION) {
        if (!$request->session()->exists('user_id')) {
            // return redirect('get_session');
            return redirect('dashboard');
            // return header("Refresh:0; url=rapidx/pats_ppd_rev/");
        }
        return $next($request);
    }
}
