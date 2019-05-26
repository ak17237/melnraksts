<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckSearchLink
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) // meklēšanas linka pārbaude
    {
        $path = explode('/',$request->path()); // saņemam meklēšanas parametrus
        $options = explode('%3E',$path[1]); // sadalam tos masīvā

        if(sizeof($options) != 6) return response()->view('errors.404'); // ja linkā nav pietiekams parametru tad nedod to apstrādāt
        
        if($options[0] == 'checkevent' || $options[0] == 'checkreservation'){ // ja linkā sākums nav pareizs

            if(!Auth::check() && $options[0] == 'checkreservation') return response()->view('errors.404');
            else return $next($request);

        }
        else return response()->view('errors.404');
    }
}
