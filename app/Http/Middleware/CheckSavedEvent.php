<?php

namespace App\Http\Middleware;

use Closure;

class CheckSavedEvent
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
        if(checkEvent($request->route('id'),1) ) return $next($request);
        else return response("There is no such event",404);
    }
}
