<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use App\Reservation;

class SearchController extends Controller
{
    public function search($options,$page){

        $search = explode('>',$options); // 0 - Meklēt pasākumu jeb rezervāciju,1 - meklēšanas teksts,2,3,4,5 - checkbox parametri

            

        $searchtype = $search[0];
        $searchtext = $search[1];
        $checkbox[] = $search[2];
        $checkbox[] = $search[3];
        $checkbox[] = $search[4];
        $checkbox[] = $search[5];
        
        if(strlen($search[1]) < 3 || strlen($search[1]) > 50){

            $search[2] = 'off';
            $search[4] = 'off';
            $search[5] = 'off';

        }
        
        if($search[0] == 'checkevent') {
            
            $type = 'event';
            $filter = ['_','_','_','_'];

            if($search[2] == 'on') $filter[0] = '%' . $search[1]  . '%';
            if($search[3] == 'on') $filter[1] = '%' . $search[1]  . '%';
            if($search[4] == 'on') $filter[2] = '%' . $search[1]  . '%';
            if($search[5] == 'on') $filter[3] = '%' . $search[1]  . '%';

            $data =  Events::where('Title','like',$filter[0])
        ->orWhere('Datefrom','like',$filter[1])
        ->orWhere('Address','like',$filter[2])
        ->orWhere('Anotation','like',$filter[3])->SimplePaginate(5,['*'], 'page', $page);
        $counter = 1;

        $count = Events::where('Title','like',$filter[0])
        ->orWhere('Datefrom','like',$filter[1])
        ->orWhere('Address','like',$filter[2])
        ->orWhere('Anotation','like',$filter[3])->count();

        }
        
        elseif($search[0] == 'checkreservation') {
            
                $type = 'reservation';
                $filter = ['_','a','a','_'];

                if($search[2] == 'on') $filter[0] = '%' . $search[1]  . '%';
                if($search[3] == 'on') $filter[1] = $search[1];
                if($search[4] == 'on') {

                    if(Events::where('Title','like','%' . $search[1] . '%')->exists())
                        $filter[2] = Events::where('Title','like','%' . $search[1] . '%')->first()->id;
                    else $filter[2] = 'a';
                }
                if($search[5] == 'on') $filter[3] = '%' . $search[1]  . '%';
            
            $data =  Reservation::where('email','like',$filter[0])
            ->orWhere('Tickets',$filter[1])
            ->orWhere('EventID','like',$filter[2])
            ->orWhere('Transport','like',$filter[3])->SimplePaginate(5,['*'], 'page', $page);
            $counter = 1;
    
            $count = Reservation::where('email','like',$filter[0])
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
    public function searchget(Request $request){

        if($request['eventsearch'] == 'checkevent') 
            $data = implode(">",$request->except('_token','reservatesearch','reservemail','reservtickets','reserveventtitle','resertransport'));
        else if($request['reservatesearch'] == 'checkreservation') 
            $data = implode(">",$request->except('_token','eventsearch','eventtitle','eventdate','eventaddress','eventanotation'));
        else $data = 'checkevent>' . $request['search'] . '>on>on>on>on';

        if($request['eventdate'] == 'off' &&  $request['reservtickets'] == 'off'){

        if(strlen($request['search']) < 3 || strlen($request['search']) > 50) // Ja ieraksta ar roku meklēšanas vārdu garāku jeb īsāku nekā vajag
            $data = 'checkevent>>off>off>off>off';

        }
        return redirect()->route('search',['options' => $data,'page' => '1']);

    }
}
