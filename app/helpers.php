<?php
use App\Reservation;
use App\Events;
use App\User;

function geteventdate($eventdata){ // datumu izvade vajadzīgajā formātā
    $exp = explode('-',$eventdata);
    $date = explode(' ',$exp[2]);
    
    
    return $date[0] . '.' . $exp[1] . '.' . $exp[0];
    
}

function geteventday($eventdata){
    $exp = explode('-',$eventdata);
    $day = explode(' ',$exp[2]);

    return $day[0];
}

function eventvalidate($request){  // sēdvietu inicializēšana ja nav

        if($request['Radio'] == "No") $request['ticketcount'] = -999;
        if($request['customRadio'] == "No" || empty($request['seatnr'])) $request['seatnr'] = 0;
        if($request['inlineDefaultRadiosExample'] == "No" || empty($request['tablenr'])) { 
            $request['tablenr'] = 0; 
            $request['seatsontablenr'] = 0; 
        }
        if($request['TransportRadio'] == "Yes") $request['transport'] = 'Patstāvīgi';     
}
function getdata($data,$default = null){ // saņem datus ja ir tukšs izvada null,jeb ja ir otrais arguments tad izvada to,ja nav tukšs izvada datus

    if(empty($data)) return $default;
    else return $data;

}
function reservinfo($id){

    $myevent = Events::find($id); // atrod vajadzīgo pasākumu
    $reservation = Reservation::where('EventID',$myevent->id)->get(); // atrod visas rezervācijas šim pasākumam
    
    $array = array();

    $ticketnumber = $seatnumber = $tablenumber = 0; // biļešu un sēdvietu tagadējais skaits
        if($reservation->isNotEmpty()){
            foreach($reservation as $reservations){
                $ticketnumber += $reservations->Tickets; // pievieno biļešu skaitu cik bija rezervēts no datubāzes
                $seatnumber += $reservations->Seats;
                $tablenumber += $reservations->Tables;
            }
        }
        if($myevent->Tickets == -999) $array[0] = $ticketinfo = "Neierobežots"; // Biļešu ierobežošanas pārbaude
        else $array[0] = $ticketinfo = $myevent->Tickets - $ticketnumber; // palikušo biļešu skaits

        if($myevent->Seatnumber == 0) $array[1] = $checkedseats = 0; // Sēdvietu un galdu pārbaude
        else $array[1] = $checkedseats = $myevent->Seatnumber - $seatnumber; // palikušo sēdvietu skaits
        if($myevent->Tablenumber == 0) $array[2] = $checkedtables = 0;
        else $array[2] = $checkedtables = $myevent->Tablenumber - $tablenumber; // palikušo galdu skaits

        if($ticketinfo == 'Neierobežots') $array[3] = $standing = $ticketinfo; // stāvvietu skaits
        else $array[3] = $standing = $ticketinfo - ($checkedseats + ($checkedtables * $myevent->Seatsontablenumber));

        return $array;
}
function linecount($string){
    
    $new = explode('<br>',$string);
    $lines = 0;

    for($i = 0;$i < count($new);$i++){
        $lines += ceil(strlen($new[$i])/161);
        if(strlen($new[$i]) == 0) $lines++;
    }
    return (int)$lines;

}
function checkAuthor($email,$id){

    $user = User::where('email', $email)->first();
    $event = Events::where('id',$id)->first();

    if($event->email != $user->email) return false;
    else return true;
}
?>