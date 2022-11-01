<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Seller;

class MyRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if($role == 'employee')
        {
             if (! $request->user()->employee)
             {
                abort(401);
             }
        }
        elseif($role == 'customer')
        {
             if (! $request->user()->customer)
             {
                abort(401);
             }
        }



        return $next($request);
    }
}
