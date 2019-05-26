<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckReservEditable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) // pārbauda rediģējamas rezervācijas pasākumam
    {
        if(Auth::user()->hasRole('Admin')) return $next($request); // ja ir administrators,tad var
        elseif(!checkEditable($request->route('id'))) { // ja nē tad jābūt rediģējamam iestatījumam ielēgtam

            $message[0] = 'Rezervācijas šim pasākumam nevar rediģēt!';
            $message[1] = 'Jūs meiģinat rediģēt rezervāciju pasākumam,kurus nevar rediģēt parasti lietotāji!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
        else return $next($request);
    }
}
