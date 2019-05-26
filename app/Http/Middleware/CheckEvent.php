<?php

namespace App\Http\Middleware;

use Closure;

class CheckEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) // pārbauda pasākuma eskistenci ar funkciju no helpers.ph[]
    {
        if(checkEvent($request->route('id'))) return $next($request);
        else {

            $message[0] = 'Pasākums nav atrasts!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kurš tika dzēsts jeb neeksistēja vispār!';
            $state = '4';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
    }
}
