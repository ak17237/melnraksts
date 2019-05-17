<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;
use App\Reservation;
use App\User;
use Auth;
use Mail;
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

        $elements = 5;
        $counter = 1;

        $user = User::where('email', Auth::user()->email)->first();
        $reservations = Reservation::where('email',$user->email)->orderBy('updated_at','DESC')->SimplePaginate($elements,['*'], 'page', $page);
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

        $checkedseats += $reservation->Seats;
        $checkedtables += $reservation->TableSeats;

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

        eventvalidate($request);

        if($request['manualreserv'] == "on") {

            $email = $request['email'];
            session(['way' => 'admins']);

        }
        else {

            $user = User::where('email', Auth::user()->email)->first();

            $email = $user->email;

            session(['way' => 'users']);

        }
        
        Reservation::create([  // ieraksta datus datubāzē 
            'email' => $email,
            'EventID' => $myevent->id,
            'Tickets' => $request['tickets'],
            'Seats' => $request['seatnr'],
            'TableNr' => $request['tablenr'],
            'TableSeats' => $request['tablecount'],
            'Transport' => $request['transport'],
            ]);

            $reserv = Reservation::all()->sortByDesc(['updated_at'])->first(); // saņemam tikko ievietoto rezervāciju

            $QRcode = generateRandomString(); // ģenerējas skaitļū un vārdu virkne
            $idarray = str_split($reserv->id); // sadalam id masīvā,lai izslēgt gadijumu,kad nejauši ģenerēta virkne var ar mazu varbūtību atkārtoties

            for($i = 0;$i < strlen($reserv->id);$i++){ // ievietojam virknē id vietās pēc katra burta(id  pirmais numurs,virknes simbols,id otrais numurs,vēl viens virknes simbols utt.)

            $QRcode = substr_replace($QRcode,$idarray[$i],$i*2,0);

            }

            $reserv->fill(['QRcode' => $QRcode]);
            $reserv->save();

            $ticket = new TemplateProcessor('Ticket-Template.docx');

        $ticket->setValue('title',$myevent->Title);
        $ticket->setValue('address',$myevent->Address);

        if(geteventdate($myevent->Datefrom) == geteventdate($myevent->Dateto))
            $date=  geteventdate($myevent->Datefrom);
        else
         $date= geteventdate($myevent->Datefrom) . '-' . geteventdate($myevent->Dateto);

        $ticket->setValue('date',$date);
        $ticket->setValue('tickets',$request['tickets']);

        if($request['seatnr'] > 0)
            $info = $request['seatnr'] . ' sēdvietas.';
        if($request['tablenr'] != 0){

            if(isset($info)){
                $info = $info . $request['tablecount'] . ' sēdvietas pie ' . $request['tablenr'] . ' galda.';
            }
            else $info = $request['tablecount'] . ' sēdvietas pie ' . $request['tablenr'] . ' galda.';
        }
        if($request['seatnr'] == 0 && $request['tablenr'] == 0) $info = $request['tickets'] . ' stāvvietas.';


        $ticket->setValue('info',$info);

        $qrCode = new QrCode($QRcode);
        header('Content-Type: '.$qrCode->getContentType());
        $qrCode->writeFile(public_path() .'/qrcode.png');

        $ticket->setImageValue('image',array('path' => public_path() .'/qrcode.png', 'width' => 200, 'height' => 200));

        $path = 'event-ticket/' . str_replace(' ', '_', $myevent->Title) . '_' . $reserv->id . '_ticket.docx';
        $ticket->saveAs($path);

        Mail::send(new Ticket($reserv,$myevent,$path));

        Storage::disk('ticket')->delete(str_replace(' ', '_', $myevent->Title) . '_' . $reserv->id . '_ticket.docx');
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

        if(sizeof($reservation->getChanges()) > 0) Mail::send(new ReservationChange($reservation,$reservation->getChanges(),$event));

        if(\Session::get('way') == 'users')
        return redirect()->route('showreservation',$id)->with('message','Rezervācija Izmainīta');
        else 
        return redirect()->route('showreservation',$id)->with('message','Rezervācija Izmainīta');
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
