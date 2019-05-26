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
    public function handle($request, Closure $next) // pārbauda rezervācijas īpašnieku
    {
        if(Auth::user()->hasRole('Admin')) return $next($request); // ja ir admnistrators var pārskatīt 
        elseif(!checkCreator(Auth::user()->email,$request->route('id'))) { // ja nav autors kļūdas lapa

            $message[0] = 'Jūs neesat rezervācijas autors!';
            $message[1] = 'Jūs nevarat piekļūt pie svešām rezervācijām!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        } // ja ir autors dod pieeju
        else return $next($request);
    }
}
