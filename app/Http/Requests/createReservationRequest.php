<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Events;
use App\Reservation;
use App\Rules\ValidReserv;
use App\Rules\CheckUser;

class createReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function attributes() // atribūtu latviskots nosaukums
    {
       return[
        'tickets' => 'Biļešu skaits',
        'seatnr' => 'Sēdvietu skaits',
        'tablecount' => 'Sēdvietu skaits pie galda',
        'name' => 'Vārds',
        'surname' => 'Uzvārds',
        'email' => 'E-pasts',
       ];
    }
    public function messages() // ziņu noasukumu izmaiņa
    {
        return[
            'max' => 'Māksimāls pieļaujamais skaits ir :max',
            'required' => ':attribute ir obligāts',
            'gte' => ':attribute jābūt lielāks vai vienāds par :value',
            'lte' => ':attribute jābūt mazāks vai vienāds par :value',
        ];
    }
    public function rules() // validācijas noteikumi
    {
        if($this->route()->getName() == 'reservationedit'){ // ja atnācām no rezervācijas rediģēšanas lapas

            $reservation = Reservation::find($this->route('id')); // saņemma tekošo rezervāiju
            $myevent = Events::where('id',$reservation->EventID)->first(); // pasākuma kuram šī rezervācija ir izveidota
            $data = reservinfo($reservation->EventID); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
            $useable = true; // lai saprast mēs atnācām no rediģēšanas vai no citas lapas tālākās funkcijās

            if($data[0] !== "Neierobežots"){  // ja biļešu skaits nav neierobežots

            $data[0] += $reservation->Tickets; // ja maina rezervāciju atgreižam šīs rezervācijas jau aizņemtās vietas.
            $data[3] += ($reservation->Tickets - ($reservation->Seats + $reservation->TableSeats));  

            }
            $data[1] += $reservation->Seats;
            $data[2] += $reservation->TableSeats; 

        }
        else {  // ja atācām no citas lapas,drīzāk tā ir izveides

        $myevent = Events::find($this->route('id')); // saņemam vajadzīgo id lai atrast vajadzīgo pasākumu
        $data = reservinfo($this->route('id')); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
        $useable = false;

        }
        
        
        $ticketinfo = $data[0]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $seatsinfo = $data[1];
        $tablesinfo = $data[2];
        $standinginfo = $data[3];

        // getdata funkcija atgreiž datus,ja ir NULL atgreiž otro parametru
        $standing = getdata($this->get('tickets'),0) - (getdata($this->get('tablecount'),0) + getdata($this->get('seatnr'),0)); // izvēlēto stāvvietu skaits

        if($ticketinfo > 2 || $ticketinfo === "Neierobežots") $ticketinfo = 2; // maksimālais bilešu skaits vienmēr ir 2
        if($tablesinfo > 2) $tablesinfo = 2;

        $maxtableseats = $myevent->Seatsontablenumber - tableSeats($myevent->id,request('tablenr')); // saņemam atlikušo vietu skaitu
        if($useable && request('tablenr') == $reservation->TableNr) $maxtableseats += $reservation->TableSeats; 
// ja netiek mainīts galdu numurs pieskaitamb galdu sēdvietu skaita pie atlikušajiem
        $rules = array();
        if(request('manualreserv') == 'on'){ // ja maunāli rezervē

            $rules['email'] = ['required',new CheckUser(request('email'),$this->route('id'))]; //  pārbaudam lietotāju ar e-pastu uz esamību un rezervāciju skaitu

        }
        // validācijas noteikumi,nevar būt vairāk par 2 biļetēm,nevar būt mazāk par sēdvietu un sēdvietu pie galda kopsummu
        // ValidReserv klase pārbauda vai stāvvietas nav vairāk par atlikušajām un pārbauda vai ir izvēlēta galda palika vietas
        if($myevent->Tickets == -999) $rules['tickets'] = ['required','lte: ' . $ticketinfo ,'gte: ' . (getdata($this->get('tablecount'),0) +  getdata($this->get('seatnr'),0)),new ValidReserv($standinginfo,$standing,1)];
        else $rules['tickets'] = ['required','lte: ' . $ticketinfo ,'gte: ' . (getdata($this->get('tablecount'),0) +  getdata($this->get('seatnr'),0)),new ValidReserv($standinginfo,$standing,1)];
        if(request('customRadio') == 'Yes') $rules['seatnr'] = 'required|max:2|lte: ' . $seatsinfo;
        if(request('inlineDefaultRadiosExample') == 'Yes') $rules['tablecount'] = ['required','lte: ' . $tablesinfo,new ValidReserv($maxtableseats,request('tablecount'),2)];
        

        return $rules;
    }
}
