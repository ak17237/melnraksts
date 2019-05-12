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
        if($request['inlineDefaultRadiosExample'] == "No" || empty($request['inlineDefaultRadiosExample'])) { 
            $request['tablenr'] = 0;
            $request['tablecount'] = 0; 
            $request['seatsontablenr'] = 0; 
        }
        if(empty($request['tablenr'])) $request['tablenr'] = 0;
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

    $ticketnumber = $seatnumber = $tableseatnumber = 0; // biļešu un sēdvietu tagadējais skaits
        if($reservation->isNotEmpty()){
            foreach($reservation as $reservations){
                $ticketnumber += $reservations->Tickets; // pievieno biļešu skaitu cik bija rezervēts no datubāzes
                $seatnumber += $reservations->Seats;
                $tableseatnumber += $reservations->TableSeats;
            }
        }
        if($myevent->Tickets == -999) $array[0] = $ticketinfo = "Neierobežots"; // Biļešu ierobežošanas pārbaude
        else $array[0] = $ticketinfo = $myevent->Tickets - $ticketnumber; // palikušo biļešu skaits

        if($myevent->Seatnumber == 0) $array[1] = $checkedseats = 0; // Sēdvietu un galdu pārbaude
        else $array[1] = $checkedseats = $myevent->Seatnumber - $seatnumber; // palikušo sēdvietu skaits
        if($myevent->Tablenumber == 0) $array[2] = $checkedtables = 0;
        else $array[2] = $checkedtables = $myevent->Tablenumber * $myevent->Seatsontablenumber - $tableseatnumber; // palikušo galdu sēdvietu skaits

        if($ticketinfo == 'Neierobežots') $array[3] = $standing = $ticketinfo; // stāvvietu skaits
        else $array[3] = $standing = $ticketinfo - ($checkedseats + $checkedtables);

        return $array;
}
function resrvcount($id){

    $myevent = Events::find($id); // atrod vajadzīgo pasākumu
    $reservation = Reservation::where('EventID',$myevent->id)->get(); // atrod visas rezervācijas šim pasākumam
    
    $array = array();

    $ticketnumber = $seatnumber = $tableseatnumber = $maxtableseats = $tables =  0; // biļešu skaits
    $same = array();
    if($reservation->isNotEmpty()){
        foreach($reservation as $reservations){
            $ticketnumber += $reservations->Tickets; // pievieno biļešu skaitu cik bija rezervēts no datubāzes
            $seatnumber += $reservations->Seats;
            $tableseatnumber += $reservations->TableSeats;
            $tableseats[] = $reservations->TableSeats;
            
            if($reservations->TableNr != 0){

            if(!in_array($reservations->TableNr,$same)) $tables++;

            }

            $same[] = $reservations->TableNr;
        }
        $maxtableseats = max($tableseats);
    }
    
    $array[0] = $ticketnumber;
    $array[1] = $seatnumber;
    $array[2] = $tableseatnumber;
    $array[3] = $maxtableseats;
    $array[4] = $tables;

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
function checkAuthor($email,$eventid){

    $user = User::where('email', $email)->first();
    $event = Events::where('id',$eventid)->first();

    if(empty($event)) return response("There is no such event",404);

    if($event->email != $user->email) return false;
    else return true;
}
function checkCreator($email,$reservid){

    $user = User::where('email', $email)->first();
    $reserv = Reservation::where('id',$reservid)->first();

    if(empty($reserv)) return response("There is no such event",404);

    if($reserv->email != $user->email) return false;
    else return true;

}
function tableSeats($eventid,$nrid){

    $reservations = Reservation::where('EventID',$eventid)->where('TableNr',$nrid)->get();
    $count = 0;
    foreach($reservations as $reserv){
        $count += $reserv->TableSeats;
    }
    return $count;
}
function checkResrvationCount($eventid,$email){

    $reservations = Reservation::where('EventID',$eventid)->where('email',$email)->get();
    $count = 0;
    foreach($reservations as $reserv){
        $count += $reserv->Tickets;
    }
    return $count;
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function checkEvent($eventid,$save = 0,$extension = null,&$status = null){
    
    $event = Events::where('id',$eventid)->first();
    
    if(empty($event)) return false;
    elseif($save != 0){
        
        if($event->Melnraksts == 1) return false;
        

        elseif($save == 2){
            if($event->VIP == 1 && $extension != $event->linkcode) {
    
                $status = 2;
                return false;
            }
            elseif($extension != $event->linkcode) {
    
                $status = 1;
                return false;
    
            }
            else return true;       
    
        }
        else return true;

    }
    else return true;

}
function checkReserv($reservid){

    $reserv = Reservation::where('id',$reservid)->first();

    if(empty($reserv)) return false;
    else return true;

}
function countbyoneVIP(&$count){
    $count++;
}
function geteventbyreservation($id,&$event){

    $reservation = Reservation::find($id);

    $event = Events::where('id',$reservation->EventID)->first(); 
}
function getuserbyemail($email){

    return User::where('email',$email)->first();

}
function checkEditable($reservid){

    $reservation = Reservation::find($reservid);
    $event = Events::where('id',$reservation->EventID)->first(); 

    if($event->Editable == 1) return true;
    else false;
}
function checkExpired($id,$route = NULL){ // pārbauda vai pasākums jau beidzās,ja beidzās tad true,ja nē tad false
    if($route === 'showreservationedit') {

        $reservation = Reservation::find($id);
        $event = Events::find($reservation->EventID);
    }
    else $event = Events::find($id);

    if(date('Y-m-d') > $event->Datefrom) return true;
    else return false;

}
?>