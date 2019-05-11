<?php

namespace App\Http\Middleware;

use Closure;

class CheckExpiredEvent
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
        if(checkExpired($request->route('id'),$request->route()->getName())){

            $message[0] = 'Pasākums jau beidzies!';
            $message[1] = 'Jūs meiģinat darboties ar pasākumu,kurš jau ir pagājis!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));
        }

        return $next($request);
    }
}
