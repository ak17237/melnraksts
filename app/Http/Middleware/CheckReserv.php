<?php

namespace App\Http\Middleware;

use Closure;

class CheckReserv
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
        if(checkReserv($request->route('id'))) return $next($request);
        else return response("There is no such reservation",404);
    }
}
