<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            
            $message[0] = 'Jūs jau ielogojāties!';
            $message[1] = 'Nevar piekļūt pie reģistrācijas vai ielogošanas formas ja jūs jau ielogojāties sistēmā!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }

        return $next($request);
    }
}
