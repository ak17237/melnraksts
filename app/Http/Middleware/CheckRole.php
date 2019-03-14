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
        if($request->user() == null){ // ja lietotājs nav ielogojies nav piekļuves
            return response('Insufficient permissions', 401);
        }
        $actions = $request->route()->getAction(); 
        $roles = isset($actions['roles']) ? $actions['roles'] : null; // saņem tiesības ja tās ir iestatītas
        
        if($request->user()->hasAnyRole($roles) || !$roles) { // pārbauda vai lietotājam ir ši tiesība un lai tā nebūtu tukša
            return $next($request);
        }
        return response('Insufficient permissions', 401); 
// karnelī vajag šo middleware pierakstīt
    }
}
