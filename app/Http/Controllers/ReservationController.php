<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use App\Reservation;
use App\User;
use Auth;
use Mail;
use PDF;
use App\Mail\ReservationChange;
use App\Mail\Ticket;
use App\Http\Requests\createReservationRequest;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Storage;

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

        $elements = 10;
        $counter = 1;

        $events = Events::where('Datefrom','>',date('Y-m-d',strtotime('-1 weeks')))->get();

        foreach($events as $event){

            $presentevents[] = $event->id;

        }
        $user = User::where('email', Auth::user()->email)->first();

        $reservations = Reservation::where('user_id',$user->id)->whereIn('EventID',$presentevents)->orderBy('updated_at','DESC')->SimplePaginate($elements,['*'], 'page', $page);
        $count = Reservation::where('user_id',$user->id)->whereIn('EventID',$presentevents)->count();

        $event = null;
        
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
        $user = User::where('id',$reservation->user_id)->first();

        $data = reservinfo($reservation->EventID); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
 
        $checkedseats = $data[1]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $checkedtables = $data[2];

        $checkedseats += $reservation->Seats;
        $checkedtables += $reservation->TableSeats;

        return view('Reservation.Reservationinfo',compact('reservation','myevent','user','checkedseats','checkedtables'));

    }
    public function showreservationedit($id){
        
        $reservation = Reservation::find($id);
        $myevent = Events::find($reservation->EventID);
        $user = User::where('id',$reservation->user_id)->first();

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
        $number = $tempnumber = 2; // cik ieraksti rādās vienā lapā // korektai skaitļu izvadei katrā lapā
        session(['way' => 'admins']);
        return view('Reservation.Reservationadmins',compact('myevent','reservation','user','count','number','tempnumber'));

    }
    public function reservationcreate(createReservationRequest $request,$id){ // Saņem ierakstītos datus uz pasākuma ID

        define("DOMPDF_UNICODE_ENABLED", true); // PDF rakstīšanai vajadzīgā formātā

        $myevent = Events::find($id); // atrod vajadzīgo pasākumu

        eventvalidate($request); // Manis izveidotā funkcija kura iedot vajadzīgās datubāzei vērtības laukiem kuri tika atslēgti,neaizpildīti

        if($request['manualreserv'] == "on") { // ja rezervācijas bija manuāla

            $email = $request['email']; // saņem ierakstīto e-pastu
            session(['way' => 'admins']); // "Atpakaļ" pogu pareizai funkcionēšanai,lai saprastu uz kurieni redirectot
            $user = User::where('email', $email)->first(); // atrod lietotāju ar šādu e-pastu

        }
        else { // ja rezervē autentificēto lietotāju

            $user = User::where('email', Auth::user()->email)->first(); // saņem to pēc e-pasta

            $email = $user->email; // saņem to e-pastu no datu bāzes

            session(['way' => 'users']); // "Atpakaļ" pogu pareizai funkcionēšanai,lai saprastu uz kurieni redirectot

        }
        
        Reservation::create([  // ieraksta datus datubāzē 
            'user_id' => $user->id,
            'EventID' => $myevent->id,
            'Tickets' => $request['tickets'],
            'Seats' => $request['seatnr'],
            'TableNr' => $request['tablenr'],
            'TableSeats' => $request['tablecount'],
            'Transport' => $request['transport'],
            ]);

            $reserv = Reservation::all()->sortByDesc(['updated_at'])->first(); // saņemam tikko ievietoto rezervāciju

            $QRcode = generateRandomString(); // ģenerējas skaitļu un vārdu nejaušā kārtībā virkne
            $idarray = str_split($reserv->id); // sadalam id masīvā,lai izslēgt gadijumu,kad nejauši ģenerēta virkne var ar mazu varbūtību atkārtoties

            for($i = 0;$i < strlen($reserv->id);$i++){ // ievietojam virknē id vietās pēc katra burta
                //id  pirmais numurs,virknes simbols,id otrais numurs,vēl viens virknes simbols utt.

            $QRcode = substr_replace($QRcode,$idarray[$i],$i*2,0);

            }

            $reserv->fill(['QRcode' => $QRcode]); // ievietojam ģenerēto kodu,kas ir kods biļetei
            $reserv->save(); // saglabājam rezervācijai
        
        $qrCode = new QrCode($QRcode); // rakstam kodu
       /*  header('Content-Type: image/png'); */
        $qrCode->writeFile(public_path() .'/qrcode.png'); // ierakstam QRkodu

        $user = User::find($reserv->user_id); // atrodam lietotāju šai rezervācijai
        $data = get_ticket_data($myevent->id,$reserv->id,$user->email); // saņemem biļetes datus no funkcijas,kura apstrādā (Pasākuma id,reservācijas,id,lietotāja e-pasts)

        $pdf = PDF::loadView('ticket', $data); // Izveidojam PDF no html skata
        $path = 'event-ticket/' . str_replace(' ', '_', $myevent->Title) . '_' . $reserv->id .'_ticket.pdf'; // pdf faila glabāšanas vieta un nosaukums
        $pdf->save($path); // saglabājam pdf
        Mail::send(new Ticket($reserv,$user->email,$myevent,$path)); // sūtam e-pastu rezervētam lietotājam ar pielikumu PDF failu

        Storage::disk('ticket')->delete(str_replace(' ', '_', $myevent->Title) . '_' . $reserv->id .'_ticket.pdf'); // pēc tam izdzēšam QRkodu un pdf failu
        Storage::disk('main')->delete('qrcode.png');
        
            
        return redirect()->route('showreservation',$reserv->id)->with('message','Pasākums rezervēts');
        
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

        $event = Events::find($reservation->EventID);

        if(sizeof($reservation->getChanges()) > 0) {
         
            $user = User::find($reservation->user_id);
            Mail::send(new ReservationChange($reservation,$user->email,$reservation->getChanges(),$event));

        }

        if(\Session::get('way') == 'users'){

            if($reservation->wasChanged())
                return redirect()->route('showreservation',$id)->with('message','Rezervācija izmainīta');
            else return redirect()->route('showreservation',$id)->with('message','Rezervācijā nebija veiktas izmaiņas');

        }
        else {

            if($reservation->wasChanged())
                return redirect()->route('showreservation',$id)->with('message','Rezervācija izmainīta');
            else return redirect()->route('showreservation',$id)->with('message','Rezervācijā nebija veiktas izmaiņas');

        }

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
