<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use App\Reservation;
use App\User;

class SearchController extends Controller
{
    public function search($options,$page){

        $search = explode('>',$options); // 0 - Meklēt pasākumu jeb rezervāciju,1 - meklēšanas teksts,2,3,4,5 - checkbox parametri
// atadlam meklēšanas parametrus kuri ir linkā
            

        $searchtype = $search[0]; // lai saglabātu vecāš vērtības skatā
        $searchtext = $search[1];
        $checkbox[] = $search[2];
        $checkbox[] = $search[3];
        $checkbox[] = $search[4];
        $checkbox[] = $search[5];
        
        if(strlen($search[1]) < 3 || strlen($search[1]) > 50){ // ja vārds ir garāks jeb īsāks nekā vajag atslēgt visus filtrus izņemot skaitļa filtrus(pēc datuma,pēc biļešu skaita)

            $search[2] = 'off';
            $search[4] = 'off';
            $search[5] = 'off';

        }
        
        if($search[0] == 'checkevent') { // ja tika meklēts pasākums
            
            $type = 'event'; // padot skatam ko meklējam lai parādītu noteikta forma tabulu
            $filter = ['_','_','_','_']; // ja filtrs kādam parametram nav ieslēgts meklēt tabulā vārdu ar garumu 1
            // un tā kā tādi veida vārdi tabulānav iepējami formu validāciju dēļ,tikai sākot no 4 simboliem,tad nekas netiks atrasts
// tas arī simulē filtra atslēgšanu
            if($search[2] == 'on') $filter[0] = '%' . $search[1]  . '%'; // ja filtrs ieslēgts meklējam to
            if($search[3] == 'on') $filter[1] = '%' . $search[1]  . '%';
            if($search[4] == 'on') $filter[2] = '%' . $search[1]  . '%';
            if($search[5] == 'on') $filter[3] = '%' . $search[1]  . '%';

            $data =  Events::where('Title','like',$filter[0]) // meklējam kādu no parametriem
        ->orWhere('Datefrom','like',$filter[1])
        ->orWhere('Address','like',$filter[2])
        ->orWhere('Anotation','like',$filter[3])->SimplePaginate(5,['*'], 'page', $page);
        $counter = 1; // lapu skaitīšanai

        $count = Events::where('Title','like',$filter[0]) // saskaitam tos
        ->orWhere('Datefrom','like',$filter[1])
        ->orWhere('Address','like',$filter[2])
        ->orWhere('Anotation','like',$filter[3])->count();

        }
        
        elseif($search[0] == 'checkreservation') { // ja ir rezervācija
            
                $type = 'reservation';
                $filter = ['_','a','a','_']; // tāpat kā augstāk tikai 2,3 vērtības ir cipari,tāpēc burti tajos būt nevar

                if($search[2] == 'on') $filter[0] = '%' . $search[1]  . '%';
                if($search[3] == 'on') $filter[1] = $search[1]; // ja ir cipari,meklējam tieši to ko vajag atrast(biļēšu skaits)
                if($search[4] == 'on') { // ja pēc pasākuma nosaukuma
// meklējam pasākumu ar tādu nosaukumu,ja nav atrats tad liekam a lai nebūtu rezeultātu,ja ir,tad ieliekam atrastos pasākkumus
                    if(Events::where('Title','like','%' . $search[1] . '%')->exists())
                        $filter[2] = Events::where('Title','like','%' . $search[1] . '%')->first()->id;
                    else $filter[2] = 'a';
                }
                if($search[5] == 'on') $filter[3] = '%' . $search[1]  . '%';
            
                if(User::where('email','like',$filter[0])->exists()){ // ja meklējam pēc e-pasta

                    $user = User::where('email','like',$filter[0])->get(); // sameklējam lietotājus
                    
                    foreach($user as $u){ // ievietojam katra lietotāja ID masīvā

                        $userid[] = $u->id;

                    }
                } // ja nav 0 jo ar 0 nav lietotāju ID
                else $userid[] = 0;

            $data =  Reservation::whereIn('user_id',$userid) // meklējam starp lietotāju id,ar pārējiem parametriem
            ->orWhere('Tickets',$filter[1])
            ->orWhere('EventID','like',$filter[2])
            ->orWhere('Transport','like',$filter[3])->SimplePaginate(5,['*'], 'page', $page);

            $counter = 1;

            $count = Reservation::whereIn('user_id',$userid) // saskaitam tos
            ->orWhere('Tickets',$filter[1])
            ->orWhere('EventID','like',$filter[2])
            ->orWhere('Transport','like',$filter[3])->count();

    
        }

        
        $number = 1;
        while($count > 5){ // precīza paginēšanas url izvade un pogas tai
            $number++;
            $count = $count - 5;
        }
        for($i = 1;$i <= $number; $i++) $pagenumber[] = $i;
        
        return view('search',compact('data','pagenumber','counter','type','searchtext','checkbox','searchtype'));

    }
    public function searchget(Request $request){ // saņem meklēšanas pieprasījumu

        if($request['eventsearch'] == 'checkevent') // ja tika meklēta sadaļa pasākumi izmest filtrus par rezervācijām
            $data = implode(">",$request->except('_token','reservatesearch','reservemail','reservtickets','reserveventtitle','resertransport'));
        else if($request['reservatesearch'] == 'checkreservation') // ja tika meklēda sadaļa rezervācijas izmest filtru par pasākumiem
            $data = implode(">",$request->except('_token','eventsearch','eventtitle','eventdate','eventaddress','eventanotation'));
        else $data = 'checkevent>' . $request['search'] . '>on>on>on>on';

        // ja lietotājs meklē tukšu lapu no headera vai no meklēšanas lapas ar izslēgtiem filtriem pēc pasākuma un pēc biļešu skaitu
        if(empty($request['eventdate']) || $request['eventdate'] == 'off' &&  $request['reservtickets'] == 'off'){
// pārbaudīt meklējamo vārdu uz garumu
        if(strlen($request['search']) < 3 || strlen($request['search']) > 50) // Ja ieraksta ar roku meklēšanas vārdu garāku jeb īsāku nekā vajag
            $data = 'checkevent>>off>off>off>off'; // izslēgt visus filtrus

        } // atdod datus funckijai search
        return redirect()->route('search',['options' => $data,'page' => '1']);

    }
}
