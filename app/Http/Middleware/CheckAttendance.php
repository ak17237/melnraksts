<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAttendance
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
        if(Auth::check()){

            if(Auth::user()->hasRole('Admin') || checkAttendance(Auth::user()->id,$request->route('id'))) return $next($request);
            else{

                $message[0] = 'Nevar piekļūt galerijai!';
                $message[1] = 'Nevar skatīt galerijas pasākumiem,kuros jūs nepiedalījāties!';
                $state = '3';

                return response()->view('errors.specificerrors',compact('message','state'));
            }

        }
    }
}
