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
        $status = 0; // sākumā kļūdas statuss ir 0
        // dodam pārbaudīt pasākumu uz linka pareizību un iedodam statusu,ja viss kārtūbā,tad atgriež true un dod piekļuvi
        if(checkEvent($request->route('id'),2,$request->route('extension'),$status)) return $next($request); 
        elseif($status == 1) return response()->view('errors.404'); // ja nav atkarībā no kļūdas statusa izvadam kļūdu,ka tādas lapas nav,jo pasākums nav vip bet links nepareizs
        elseif($status == 2) { // pasākum ir VIP bet links ir tik un tā nepareizs

            $message[0] = 'Šis ir VIP pasākums!';
            $message[1] = 'Lai piekļūt ši pasākima rezervācijai ir nepieciešams links!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
        else { // ja ir false un statusa nav,tad pasākums nebija atrasts

            $message[0] = 'Pasākums nav atrasts!';
            $message[1] = 'Jūs meiģinat piekļūt pie pasākuma,kurš tika dzēsts jeb neeksistēja vispār!';
            $state = '4';

            return response()->view('errors.specificerrors',compact('message','state'));

        }
    }
}
