<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RedirectToDashboard
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
        if (auth()->check()) {
            if (count(auth()->user()->roles) > 0) {
                return redirect()->route('admin.dashboard');
            }elseif(auth()->user()->employee){
                return redirect()->route('employee.dashboard');
            }elseif(auth()->user()->customer){
                return redirect()->route('customer.dashboard');
            }
            else{
                return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}
