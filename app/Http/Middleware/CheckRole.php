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
    public function handle($request, Closure $next)
    {
        if($request->user() == null){
             // ja lietotājs nav ielogojies nav piekļuves
            $message[0] = 'Nepietiekamas tiesības!';
            $message[1] = 'Jums ir jāautorizējas jeb jāreģistrējas lai piekļūtu šai lapai!';
            $state = '1';

            return response()->view('errors.specificerrors',compact('message','state'));
        }
        $actions = $request->route()->getAction(); 
        $roles = isset($actions['roles']) ? $actions['roles'] : null; // saņem tiesības ja tās ir iestatītas
        
        if($request->user()->hasAnyRole($roles) || !$roles) { // pārbauda vai lietotājam ir ši tiesība un lai tā nebūtu tukša
            return $next($request);
        }
            $message[0] = 'Nepietiekamas tiesības!';
            $message[1] = 'Parasteim lietotājiem ir aizliegts atrasties šajā lapā!';
            $state = '1';

            return response()->view('errors.specificerrors',compact('message','state')); 
// karnelī vajag šo middleware pierakstīt
    }
}
