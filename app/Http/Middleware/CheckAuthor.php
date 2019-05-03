<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;
use App\Events;

class CheckAuthor
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
        if(!checkAuthor(Auth::user()->email,$request->route('id'))){

            $message[0] = 'Jums nepietiek tiesības lai piekļūt šai lapai!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kura autors neesat jūs!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
        else return $next($request);
    }
}
