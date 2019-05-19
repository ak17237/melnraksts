<?php

namespace App\Http\Middleware;

use Closure;
use App\Events;

class CheckExpiredEvent
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
        if($request->route()->getName() == 'downloadreport'){

            $event = Events::find($request->route('id'));
            
            if(date("Y-m-d") > $event->Datefrom) return $next($request);
            else {

                $message[0] = 'Pasākuma atsakite vēl nav gatava!';
                $message[1] = 'Jūs meiģinat piekļūt pie atskaites pasākumam kurš vēl nav beidzies!';
                $state = '3';

                return response()->view('errors.specificerrors',compact('message','state'));

            }

        }
        elseif($request->route()->getName() == 'showqrcode'){

            $event = Events::find($request->route('id'));

            if(date("Y-m-d") >= $event->Datefrom && date("Y-m-d") <= $event->Dateto) return $next($request);
            else{

                $message[0] = 'Ši lapa ir pieejama tikai pasākuma pirmajā dienā!';
                $message[1] = 'Nedrīkst piekļūt pie biļešu skanēšanas pirms vai pēc pasākums sākās!';
                $state = '3';

                return response()->view('errors.specificerrors',compact('message','state'));

            }

        }
        elseif(checkExpired($request->route('id'),$request->route()->getName())){

            $message[0] = 'Pasākums jau beidzies!';
            $message[1] = 'Jūs meiģinat darboties ar pasākumu,kurš jau ir pagājis!';
            $state = '3';

            return response()->view('errors.specificerrors',compact('message','state'));
        }

        return $next($request);
    }
}
