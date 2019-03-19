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
    public function showreservationcreate($id){
        $myevent = Events::find($id);

        if(!empty($myevent) && $myevent->Melnraksts == 1) return response("There is no such event",404); // Ja atrastais id ir melnraksts vai neeksistē izdod kļūdu
        else if(empty($myevent)) return response("There is no such event",404);
        
        $data = reservinfo($id); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām

        $ticketinfo = $data[0]; 
        $checkedseats = $data[1]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $checkedtables = $data[2];
        $standing = $data[3];
        

        $description = str_replace("\r\n",'<br>',$myevent->Description);
        
        return view('Reservation.Reservationcreate',compact('myevent','checkedtables','checkedseats','ticketinfo','standing','description'));
    }
    public function reservationcreate(createReservationRequest $request,$id){
        
        $myevent = Events::find($id);
        $user = User::where('email', Auth::user()->email)->first();

        eventvalidate($request);
        Reservation::create([  // ieraksta datus datubāzē 
            'email' => $user->email,
            'EventID' => $myevent->id,
            'Tickets' => $request['ticketcount'],
            'Seats' => $request['seatnr'],
            'TableNr' => $request['tablenr'],
            'TableSeats' => $request['tablecount'],
            'Transport' => $request['transport'],
            ]);
        return redirect()->back()->with('message','Pasākums rezervēts');
        
    }
}
