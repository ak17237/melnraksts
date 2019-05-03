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
        else {

            $message[0] = 'Pasākums nav atrasts!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kurš tika dzēsts jeb neeksistēja vispār!';
            $state = '4';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
    }
}
