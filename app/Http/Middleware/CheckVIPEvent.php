<?php

namespace App\Http\Middleware;

use Closure;

class CheckVIPEvent
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
        $status = 0;
        
        if(checkEvent($request->route('id'),2,$request->route('extension'),$status)) return $next($request);
        elseif($status == 1) return response("This page doesn't exist",404);
        elseif($status == 2) return response("This event is VIP, access only with links",404); 
        else return response("There is no such event",404);
    }
}
