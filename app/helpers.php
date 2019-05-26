<?php
use App\Reservation;
use App\Events;
use App\User;

function geteventdate($eventdata){ // datumu izvade vajadzīgajā formātā no datubāzes
    $exp = explode('-',$eventdata);
    $date = explode(' ',$exp[2]);
    
    
    return $date[0] . '.' . $exp[1] . '.' . $exp[0];
    
}

function geteventday($eventdata){ // dienas izvade no datuma
    $exp = explode('-',$eventdata);
    $day = explode(' ',$exp[2]);

    return $day[0];
}

function eventvalidate($request){  // Ja lauki ir deaktivēti tad iedod tiem vajadzīgās vērtības

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
function reservinfo($id){ // izvada masīvu ar palikušām rezervāciajas vieām,arguments pasākuma ID

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
function resrvcount($id){ // izvada masīvā rezervācijas kopējo skaitu,biļešu,sēdvietu,galdu sēdvietu,maksimālo rezervēto skaitu pie viena galda un rezervēto galdu skaitu

    $myevent = Events::find($id); // atrod vajadzīgo pasākumu
    $reservation = Reservation::where('EventID',$myevent->id)->get(); // atrod visas rezervācijas šim pasākumam
    
    $array = array();

    $ticketnumber = $seatnumber = $tableseatnumber = $maxtableseats = $tables =  0; // biļešu skaits
    $tableseats = array();
    if($reservation->isNotEmpty()){ // ja pasākumam ir rezerācijas pieskaita katram mainīgajam savu vērtību
        foreach($reservation as $reservations){
            $ticketnumber += $reservations->Tickets; // pievieno biļešu skaitu cik bija rezervēts no datubāzes,sēdvietas,galud sēdvietas
            $seatnumber += $reservations->Seats;
            $tableseatnumber += $reservations->TableSeats;
            
            if($reservations->TableNr != 0){ // ja galds ir rezervēts tā vērtība nebūs 0
            
                if (!isset($tableseats[$reservations->TableNr])) { // ja masīvs atslēgas vēl nav tad tā ir pirmā vērtība tajā

                    $tableseats[$reservations->TableNr] = $reservations->TableSeats; // izveidojam to ar vērtību sēdvietas pie tā galda
                    $tables++; // pievienojam mainīgajam +1,kas nozīmē ka galds ir rezervēts jo vismaz viens cilvēks tajā sēž

                 }
                 else
                    $tableseats[$reservations->TableNr] += $reservations->TableSeats; // ja bija pieskaitam vēl
            }          
        }
        $maxtableseats = max($tableseats); // saņemam maskimālo rezervētu sēdvietu skaitu pie galda
    }
    
    $array[0] = $ticketnumber;
    $array[1] = $seatnumber;
    $array[2] = $tableseatnumber;
    $array[3] = $maxtableseats;
    $array[4] = $tables;

    return $array;

}
function linecount($string){ // saskaita cik ir rindas dotajai simbolu virknei,rezervācijas izveides lapai
    
    $new = explode('<br>',$string); // sadalam simbolu virkni masīvā,pēc rindas atstarpes br
    $lines = 0;

    for($i = 0;$i < count($new);$i++){ // masīvs skaita cik ir rindas katrā rindā,ja viena rinda ir 161 simbols

        $lines += ceil(strlen($new[$i])/161);
        if(strlen($new[$i]) == 0) $lines++;

    }
    return (int)$lines;

}
function checkAuthor($email,$eventid){ // pārbauda pasākuma autoru

    $user = User::where('email', $email)->first(); // saņem lietotāju saņem pasākumu
    $event = Events::where('id',$eventid)->first();

    if(empty($event)) return response("There is no such event",404); // ja pasākums neeskistē

    if($event->user_id != $user->id) return false; // ja pasākuma autors nav lietotājs ar kuru salīdzinam false
    else return true; // ja ir true
}
function checkCreator($email,$reservid){ // pārbauda rezervācijas īpašnieku

    $user = User::where('email', $email)->first(); // sanem lietotāju un rezervāciju
    $reserv = Reservation::where('id',$reservid)->first();

    if(empty($reserv)) return response("There is no such event",404); // ja rezervācijas neeksitē

    if($reserv->user_id != $user->id) return false; // ja rezervācijas īpašnieks nav lietotājs ar kuru salīdzinam false
    else return true; // ja ir true

}
function tableSeats($eventid,$nrid){ // atdod sēdvietu skaitu pie noteikta galda noteiktajam pasākumam

    $reservations = Reservation::where('EventID',$eventid)->where('TableNr',$nrid)->get(); // saņem rezervācijas pasākumam pie noteikta galda
    $count = 0;
    foreach($reservations as $reserv){ // saskaita cik ir sēdvietas pie tā galda
        $count += $reserv->TableSeats;
    }
    return $count;
}
function checkResrvationCount($eventid,$email){ // pārbauda rezervāciju skaitu noteiktam pasākumam noteiktam lietotājam

    $user = User::where('email',$email)->first(); // saņem lietotāju pēc e-pasta
    $reservations = Reservation::where('EventID',$eventid)->where('user_id',$user->id)->get(); // saņem rezervācijas lietotājam šajā pasākumā
    $count = 0;
    foreach($reservations as $reserv){ // saskaita cik ir biļetes šim lietotājam uz šo pasākumu
        $count += $reserv->Tickets;
    }
    return $count;
}
function generateRandomString($length = 10) { // nejaušas simbolu virknes ģenerācija
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // simbolu virkne no kurienes ņemt nejaušus skaitļus
    $charactersLength = strlen($characters); // simbolu virknes garums no kurienes ņemam simbolus
    $randomString = '';
    for ($i = 0; $i < $length; $i++) { // length nejaušas simbolu virknes garumā ieraksta simbolus nejaušā secībā.
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function checkEvent($eventid,$save = 0,$extension = null,&$status = null){ // middleware funkcija,pārbauda pasākuma datus
    
    $event = Events::where('id',$eventid)->first(); // saņem pasākumu pēc ID
    
    if(empty($event)) return false; // ja pasākuma nav,tad false
    elseif($save != 0){ // ja save arguments nav 0 tad pārbaudam uz melnrakstiem
        
        if($event->Melnraksts == 1) return false;
        

        elseif($save == 2){ // ja save 2 tad pārbaudam uz VIP
            if($event->VIP == 1 && $extension != $event->linkcode) { // ja pasākuma ir VIP un padotais arguments extension nav vienāds ar datubāzes
    // statuss ir 2 un izvadam false
                $status = 2;
                return false;
            }
            elseif($extension != $event->linkcode) { // ja pasākums nav VIP,bet tā links - argumnets extentison,nav pareizs,tad statuss 1 un false
    
                $status = 1;
                return false;
    
            } // citos gadījumos links ir pareizs
            else return true;       
    
        } // ja save nav 0 un nav 2,tad ir vajadzīga tikai pasākuma eksistences un melnraksta pārbaude
        else return true;

    } // ja save ir 0,tad ir vajadzīga tikai pasākuma eksistences  pārbaude
    else return true;

}
function checkReserv($reservid){ // rezervācijas eksistences pārbaude

    $reserv = Reservation::where('id',$reservid)->first();

    if(empty($reserv)) return false;
    else return true;

}
function countbyoneVIP(&$count){ // palielināt skaitu par 1,izmantots tikai home.blade.php lai sakaitīt vip pasākumus lapā
    $count++;
}
function geteventbyreservation($id,&$event){ // saņemam pasākumu,pēc rezervācijas ID

    $reservation = Reservation::find($id);

    $event = Events::where('id',$reservation->EventID)->first(); 
}
function geteventbyid($id){ // saņemam pasākumu pēc tā ID

    return Events::find($id);

}
function getuserbyid($id){ // saņemam lietotāju pēc tā ID

    return User::where('id',$id)->first();

}
function checkEditable($reservid){ // pārbaudam vai dotā rezervācija var būt rediģējama šim pasākumam

    $reservation = Reservation::find($reservid);
    $event = Events::where('id',$reservation->EventID)->first(); // ssaņemam pasākumu

    if($event->Editable == 1) return true; //pārbaude
    else false;
}
function checkExpired($id,$route = NULL){ // pārbauda vai pasākums jau beidzās,ja beidzās tad true,ja nē tad false
    if($route === 'showreservationedit') { // ja route ir showreservationedit,tad ID būs rezervācijas ID

        $reservation = Reservation::find($id); 
        $event = Events::find($reservation->EventID);
    }
    else $event = Events::find($id);
    
    if(date('Y-m-d') >= $event->Datefrom) return true; // ja šodienas datums ir lielāks par pasākuma sākuma datumu,tad true un pasākums beidzās
    else return false;

}
function get_ticket_data($eventid,$reservid,$email){ // saņemam biļetes datus html faila izveidei biļetes ģenerācijai

    $myevent = Events::find($eventid);
    $reserv = Reservation::find($reservid);
    $user = User::where('email',$email)->first(); // saņemam vajadzīgo pasākumu,rezervāiju un lietotāju

    $data = array();

    $data['title'] = $myevent->Title; // ievietojam masīvā datus
    $data['address'] = $myevent->Address;

    if(geteventdate($myevent->Datefrom) == geteventdate($myevent->Dateto)) // datuma validēšana formātā kur "-" vietā ir "."
        $data['date'] =  geteventdate($myevent->Datefrom);
    else
     $data['date'] = geteventdate($myevent->Datefrom) . '-' . geteventdate($myevent->Dateto);

    $data['ticket'] = $reserv->Tickets; // biļešu skaits

    if($reserv->Seats > 0) // sēdvietu skaits
        $data['info'] = $reserv->Seats . ' sēdvietas.';
    if($reserv->TableNr != 0){

        if(isset($data['info'])){ // info par rezervācijām tekstā
            $data['info'] = $data['info'] . $reserv->TableSeats . ' sēdvietas pie ' . $reserv->TableNr . '. galda.';
        }
        else $data['info'] = $reserv->TableSeats . ' sēdvietas pie ' . $reserv->TableNr . '. galda.';
    }
    $data['name'] = $user->First_name . ' ' . $user->Last_name; // rezervētā cilvēka vārds uzvārds
    if($reserv->Seats == 0 && $reserv->TableNr == 0) $data['info'] = $reserv->Tickets . ' stāvvietas.';

    return $data;

}
function attendance($eventid){ // pārbaudam pasākuma apmeklējumu

    $reservations = Reservation::where('EventID',$eventid)->get(); // rezervācijas pasākumam
    $data = array();

    $ticketnumber = $seatnumber = $tablenumber = $standnumber = 0;
    $same = array();

    foreach($reservations as $r){ // katrai rezervācijai

        if($r->Attendance == true){ // ja dotā rezervāija ir ar statusu apmeklēta
            
            $ticketnumber += $r->Tickets; // pievienojam datus
            $seatnumber += $r->Seats;

            if($r->TableNr != 0){ // galdu rezervācija ja ir vismaz viens cilvēks pie tā

                if(!in_array($r->TableNr,$same)) $tablenumber++;
        
            }
            $same[] = $r->TableNr;
            if(($r->Seats + $r->TableSeats) < $r->Tickets) // ja šai rezervācijai biļešu skaits ir lielāks nekā sēdvietu un galda sēdvietu skaitu tad pievienojam pie stāvvietām
                $standnumber += $r->Tickets - ($r->Seats + $r->TableSeats);
        }
    
    }

    $data[0] = $ticketnumber;
    $data[1] = $seatnumber;
    $data[2] = $tablenumber;
    $data[3] = $standnumber;

    return $data;
    
}
function checkAttendance($userid,$eventid){ // pārbauda lietotāja apmeklējumu pasākumam

    $user = User::find($userid);
    $reservations  = Reservation::where('EventID',$eventid)->get(); // saņemam datus

    foreach($reservations as $r){ // ja rezervācijas lietotājam ir vismaz viens true,tad apmeklēja un var saņemt piekļuvi pie pasākuma galerijas un vietām tikai apmeklētājiem

        if($r->Attendance == true && $r->user_id == $user->id) return true;

    }
    return false;

}
?>