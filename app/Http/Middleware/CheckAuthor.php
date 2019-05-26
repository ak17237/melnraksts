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
    public function handle($request, Closure $next) // pārbauda pasākuma autoru autentificētam lietotājam
    { 
        if(!checkAuthor(Auth::user()->email,$request->route('id'))){
            // izmanto funkciju no helpers.php,ja nav atuors kļūda
            $message[0] = 'Jums nepietiek tiesības lai piekļūt šai lapai!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kura autors neesat jūs!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
        else return $next($request);
    }
}
