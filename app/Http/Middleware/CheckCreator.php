<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckCreator
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
        elseif(!checkCreator(Auth::user()->email,$request->route('id'))) return response('Insuffisent permissons!',403);
        else return $next($request);
    }
}
