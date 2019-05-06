<?php

namespace App\Http\Middleware;

use Closure;

class CheckVIPEvent
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
        $status = 0;
        
        if(checkEvent($request->route('id'),2,$request->route('extension'),$status)) return $next($request);
        elseif($status == 1) return response()->view('errors.404');
        elseif($status == 2) {

            $message[0] = 'Šis ir VIP pasākums!';
            $message[1] = 'Lai piekļūt ši pasākima rezervācijai ir nepieciešams links!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
        else {

            $message[0] = 'Pasākums nav atrasts!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kurš tika dzēsts jeb neeksistēja vispār!';
            $state = '4';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
    }
}
