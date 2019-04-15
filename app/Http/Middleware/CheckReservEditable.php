<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckReservEditable
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
        if(Auth::user()->hasRole('Admin')) return $next($request);
        elseif(!checkEditable($request->route('id'))) return response('Insufficient permissions',401);
        else return $next($request);
    }
}
