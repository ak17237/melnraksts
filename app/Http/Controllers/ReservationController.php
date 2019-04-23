<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use App\Reservation;
use App\User;
use Auth;
use App\Http\Requests\createReservationRequest;

class ReservationController extends Controller
{
    public function showreservationcreate($id,$extension){
        $myevent = Events::find($id);
        
        $data = reservinfo($id); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām

        $ticketinfo = $data[0]; 
        $checkedseats = $data[1]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $checkedtables = $data[2];
        $standing = $data[3];
        

        $description = str_replace("\r\n",'<br>',$myevent->Description);
        
        return view('Reservation.Reservationcreate',compact('myevent','checkedtables','checkedseats','ticketinfo','standing','description'));
    }
    public function showreservationusers($page){

        $elements = 5;
        $counter = 1;

        $user = User::where('email', Auth::user()->email)->first();
        $reservations = Reservation::where('email',$user->email)->SimplePaginate($elements,['*'], 'page', $page)->sortByDesc(['updated_at']);
        $event = null;

        $count = Reservation::where('email',$user->email)->count();
        
        $number = 1;
        while($count > $elements){ // precīza paginēšanas url izvade un pogas tai
            $number++;
            $count = $count - $elements;
        }
        for($i = 1;$i <= $number; $i++) $pagenumber[] = $i;
        session(['way' => 'users']);
        return view('Reservation.Reservationusers',compact('pagenumber','reservations','event','counter'));

    }
    public function showreservation($id){

        $reservation = Reservation::find($id);
        $myevent = Events::find($reservation->EventID);
        $user = User::where('email',$reservation->email)->first();

        $data = reservinfo($reservation->EventID); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
 
        $checkedseats = $data[1]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $checkedtables = $data[2];

        return view('Reservation.Reservationinfo',compact('reservation','myevent','user','checkedseats','checkedtables'));

    }
    public function showreservationedit($id){
        
        $reservation = Reservation::find($id);
        $myevent = Events::find($reservation->EventID);
        $user = User::where('email',$reservation->email)->first();

        $data = reservinfo($reservation->EventID); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
 
        $checkedseats = $data[1]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $checkedtables = $data[2];

        $checkedseats += $reservation->Seats;
        $checkedtables += $reservation->TableSeats;

        return view('Reservation.Reservationedit',compact('reservation','myevent','user','checkedseats','checkedtables'));

    }
    public function showreservationadmins($id){

        $myevent = Events::find($id);
        $reservation = Reservation::where('EventID',$id)->get();
        
        $count = $reservation->count();
        $number = $tempnumber = 5; // cik ieraksti rādās vienā lapā // korektai skaitļu izvadei katrā lapā
        session(['way' => 'admins']);
        return view('Reservation.Reservationadmins',compact('myevent','reservation','user','count','number','tempnumber'));

    }
    public function reservationcreate(createReservationRequest $request,$id){
        
        $myevent = Events::find($id);
        $user = User::where('email', Auth::user()->email)->first();
        
        eventvalidate($request);
        Reservation::create([  // ieraksta datus datubāzē 
            'email' => $user->email,
            'EventID' => $myevent->id,
            'Tickets' => $request['tickets'],
            'Seats' => $request['seatnr'],
            'TableNr' => $request['tablenr'],
            'TableSeats' => $request['tablecount'],
            'Transport' => $request['transport'],
            ]);
        return redirect()->back()->with('message','Pasākums rezervēts');
        
    }
    public function reservationedit(createReservationRequest $request,$id){

        $reservation = Reservation::find($id);

        eventvalidate($request);

        $reservation->fill([    // ieraksta izmainīšana 
            'Tickets' => $request['tickets'],
            'Seats' => $request['seatnr'],
            'TableNr' => $request['tablenr'],
            'TableSeats' => $request['tablecount'],
            'Transport' => $request['transport'],
            ]);
        $reservation->save();

        if(\Session::get('way') == 'users')
        return redirect()->route('showreservation',$id)->with('message','Rezervācija Izmainīta');
        else 
        return redirect()->route('showreservationadmins',$reservation->EventID)->with('message','Rezervācija Izmainīta'); 
        // kad back pogas strādās pareizi redirects būs uz apskates lapu tā pat kā augstaāk

    }
    public function reservationdelete($id){

        $reservation = Reservation::find($id);
        $myevent = Events::where('id',$reservation->EventID)->first();
        $reservation->delete();

        if(\Session::get('way') == 'users')
        return redirect()->route('reservationusers',1)->with('message','Rezervācija Dzēsta');
        else 
        return redirect()->route('showreservationadmins',$myevent->id)->with('message','Rezervācija Dzēsta');

    }
    
}
