<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;
use App\Events;

class CheckAuthor
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
        if(!checkAuthor(Auth::user()->email,$request->route('id'))) return response('Insuffisent permissons!',403);
        else return $next($request);
    }
}
