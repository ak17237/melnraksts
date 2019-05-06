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
        else {
            
            $message[0] = 'Rezervācijas nav atrasta!';
            $message[1] = 'Jūs meiģinat piekļūt pie rezervācija,kura tika dzēsta jeb neeksistēja vispār!';
            $state = '4';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
    }
}
