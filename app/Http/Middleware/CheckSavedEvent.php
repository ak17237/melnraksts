<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckSavedEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) // ja pasākums ir melnrakstots
    {
        if(checkEvent($request->route('id'),1)) return $next($request); // pārbaudam vai viņs ir melnrakstos
        elseif(Auth::check() && Auth::user()->hasRole('Admin')) return $next($request); // ja ir melnrakstos,tad tikai admina piekļuve
        else{
            
            $message[0] = 'Pasākums nav atrasts!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kurš tika dzēsts jeb neeksistēja vispār!';
            $state = '4';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
    }
}
