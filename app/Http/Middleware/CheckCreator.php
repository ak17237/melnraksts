<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckCreator
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
        if(Auth::user()->hasRole('Admin')) return $next($request);
        elseif(!checkCreator(Auth::user()->email,$request->route('id'))) {

            $message[0] = 'Jūs neesat rezervācijas autors!';
            $message[1] = 'Jūs nevarat piekļūt pie svešām rezervācijām!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
        else return $next($request);
    }
}
