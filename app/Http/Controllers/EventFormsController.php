<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\createEventRequest;

use App\Events;
use App\Reservation;
use App\User;
use Auth;

class EventFormsController extends Controller
{
    public function showcreate(){ // formas izvade ar pareizu datuma formātu
        date_default_timezone_set("Europe/Riga");
        $date = date("Y-m-d");

        return view('Event_forms.Eventcreate',compact('date'));

    }
    public function showedit($id){ // pasākumu rediģēšanas formas izvade
    // vajag pievienot pārbaudi vai ir tāds pasākums līdzīgi kā rezervācijā
        $myevent = Events::find($id);

        if($myevent->Tickets == -999) $checkedtickets = false; // pārbaude vai deaktivēt inputus un saglabāt radio izvēles
        else $checkedtickets = true;
        if($myevent->Seatnumber == 0) $checkedseats = false;
        else $checkedseats = true;
        if($myevent->Tablenumber == 0) $checkedtables = false;
        else $checkedtables = true;
        
        return view('Event_forms.Eventedit',['myevent' => $myevent,'checkedseats' => $checkedseats,'checkedtables' => $checkedtables,
        'checkedtickets' => $checkedtickets]);
    }
    public function showsavedevents($page){ // parāda saglabātos pasākumus sākumā nesen izmainītos,kuriem ir melnraksts 1

        $counter = 1;

        $user = User::where('email', Auth::user()->email)->first();

        $data = Events::where('Melnraksts',1)->where('email',$user->email)->SimplePaginate(5,['*'], 'page', $page)->sortByDesc(['updated_at']);
        $count = Events::where('Melnraksts',1)->where('email',$user->email)->count();
        $number = 1;
        while($count > 5){ // precīza paginēšanas url izvade un pogas tai
            $number++;
            $count = $count - 5;
        }
        for($i = 1;$i <= $number; $i++) $pagenumber[] = $i;
        return view('Event_forms.Savedevents',compact('data','pagenumber','counter'));
    }
    public function showevent($id){

        $myevent = Events::find($id);

        $description = str_replace("\r\n",'<br>',$myevent->Description);

        return view('Event_forms.Eventinfo',compact('myevent','description'));
    }
    public function create(createEventRequest $request){ // pasākumu izveide un saglabāšana datu bāzē kļūdas pārbauda izveidotais request 
        
        if($request['action'] == 'save') $melnraksts = 1; // pārbaude vai saglabāt kā melnrakstu vai publicēt
        else $melnraksts = 0;

        eventvalidate($request); // funkcija no helpers.php

        $message = array( // ziņas izvade atkarībā no melnraksta statusa
            1 => 'saglabāts!',
            0 => 'izveidots!'
        );

        if(empty($request['vipswitch'])) $vip = 0;
        else $vip = 1;

        if(empty($request['editableswitch'])) $editable = 0;
        else $editable = 1;

        if($vip == 1){

        $info = 'VIP';
        $linkcode = generateRandomString();

        }else {
            $info = 0;
            $linkcode = "show";
        }

        $user = User::where('email', Auth::user()->email)->first();     
        Events::create([  // ieraksta datus datubāzē 
        'Title' => $request['title'],
        'Datefrom' => $request['datefrom'],
        'Dateto' => $request['dateto'],
        'Address' => $request['address'],
        'Seatnumber' => $request['seatnr'],
        'Tablenumber' => $request['tablenr'],
        'Seatsontablenumber' => $request['seatsontablenr'],
        'Anotation' => $request['anotation'],
        'Description' => $request['description'],
        'Tickets' => $request['ticketcount'],
        'Melnraksts' => $melnraksts, // melnraksta status ir atkarīgs no kura poga tika uzpiesta
        'VIP' => $vip,
        'Editable' => $editable,
        'email' => $user->email,
        'linkcode' => $linkcode,
        ]);

        return redirect()->back()->with('message','Pasākums ir veiksmīgi ' . $message[$melnraksts])->with('info',$info);
        
    }
    public function edit(createEventRequest $request,$id){

        $myevent = Events::find($id);
        $route = array( // atgirež melnrakstos vai publicētajos atkarībā no action pogas
            1 => '/saved-events-1',
            0 => '/'
        );
        $message = array( // ziņas izvade
            2 => 'saglabāts!', // ja saglabāts
            1 => 'publicēts!', // ja publicēts(atnāca no melnrakstiem)
            0 => 'izmainīts!' // ja publicēts(atnāca no slidera un reiģēja)
        );
        
        if($request['action'] == 'save') { // ja saglabāts
            $status = 2; // izvadīt ziņu par saglabāšanu ($status ir ziņas numurs $message)
            $index = 1; // jebkurā gadijumā rādīt melnrakstu sarakstu ($index ir url numurs $route)
        }
        else {
            $status = $myevent->Melnraksts; // ja tika rdiģēts bez kļūdām,tad saglabāt ziņu atkarībā kāds status ir pirms izmainīšanas
            $index = 0; // ja publicēts tad melnraksts ir 0
        }

        eventvalidate($request); // funkcija no helpers

        if($request['vipswitch'] == "off") $vip = 0;
        else $vip = 1;

        if($request['editableswitch'] == "off") $editable = 0;
        else $editable = 1;

        if($vip == $myevent->VIP){
            
            $linkcode = $myevent->linkcode;
            $info = 0;
        }
        elseif($vip == 1){
        
        $linkcode = generateRandomString();
        $info = "VIP";
        
        }
        else{

            $linkcode = "show";
            $info = 0;
        }

        $myevent->fill([    // ieraksta izmainīšana 
            'Title' => $request['title'],
            'Datefrom' => $request['datefrom'],
            'Dateto' => $request['dateto'],
            'Address' => $request['address'],
            'Seatnumber' => $request['seatnr'],
            'Tablenumber' => $request['tablenr'],
            'Seatsontablenumber' => $request['seatsontablenr'],
            'Anotation' => $request['anotation'],
            'Description' => $request['description'],
            'Tickets' => $request['ticketcount'],
            'Melnraksts' => $index,
            'VIP' => $vip,
            'Editable' => $editable,
            'linkcode' => $linkcode,
            ]);
        $myevent->save();

        return redirect($route[$myevent->Melnraksts])->with('message','Pasākums ir veiksmīgi ' . $message[$status])->with('info',$info);

}
    public function delete($id){ // ieraksta dzēšana

        $myevent = Events::find($id);
        $reservations = Reservation::where('EventID',$id)->get();

        foreach($reservations as $r){

            $r->delete();

        }

        if($myevent->Melnraksts == 1){ // ja dzēsts melnraksts atgriezt uz melnrakstiem

            Events::find($id)->delete();
            return redirect('/saved-events-1')->with('message','Pasākums ir dzēsts.');
    
            }
            else{ // ja nē atgriez galvenā lapā
    
                Events::find($id)->delete();
                return redirect()->route('home')->with('message','Pasākums ir dzēsts.');
    
            }

    }

}
