<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) // lietotāju tiesību pārbaude
    {
        if($request->user() == null){
             // ja lietotājs nav ielogojies nav piekļuves
            $message[0] = 'Nepietiekamas tiesības!';
            $message[1] = 'Jums ir jāautorizējas, lai piekļūtu šai lapai!';
            $state = '1';

            return response()->view('errors.specificerrors',compact('message','state'));
        }
        $actions = $request->route()->getAction(); // saņem visas actions no web.php
        $roles = isset($actions['roles']) ? $actions['roles'] : null; // ja tieši 'roles' middleware actions ir iestatītas tad ievietojam tās,ja nē tad null,pārbaude uz tiesībām nav
        
        if($request->user()->hasAnyRole($roles) || !$roles) { // pārbauda vai lietotājam ir ši tiesība, jeb vai tiesības ir iestatītas
            return $next($request);
        }
            $message[0] = 'Nepietiekamas tiesības!';
            $message[1] = 'Parasteim lietotājiem ir aizliegts atrasties šajā lapā!';
            $state = '1';

            return response()->view('errors.specificerrors',compact('message','state')); 
// karnelī vajag šo middleware pierakstīt
    }
}
