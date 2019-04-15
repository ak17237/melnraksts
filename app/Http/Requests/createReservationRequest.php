<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Events;
use App\Reservation;
use App\Rules\ValidReserv;

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
    public function rules()
    {
        if($this->route()->getName() == 'reservationedit'){

            $reservation = Reservation::find($this->route('id'));
            $myevent = Events::where('id',$reservation->EventID)->first();
            $data = reservinfo($reservation->EventID); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
            $useable = true;
            if($data[0] !== "Neierobežots"){

            $data[0] += $reservation->Tickets;
            $data[3] += ($reservation->Tickets - ($reservation->Seats + $reservation->TableSeats));

            }
            $data[1] += $reservation->Seats;  

        }
        else { 

        $myevent = Events::find($this->route('id')); // saņemam vajadzīgo id lai atrast vajadzīgo pasākumu
        $data = reservinfo($this->route('id')); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
        $useable = false;

        }
        
        
        $ticketinfo = $data[0]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $seatsinfo = $data[1];
        $tablesinfo = $data[2];
        $standinginfo = $data[3];

        $standing = getdata($this->get('tickets'),0) - (getdata($this->get('tablecount'),0) + getdata($this->get('seatnr'),0)); // izvēlēto stāvvietu skaits

        if($ticketinfo > 2 || $ticketinfo === "Neierobežots") $ticketinfo = 2;
        if($tablesinfo > 2) $tablesinfo = 2;

        $maxtableseats = $myevent->Seatsontablenumber - tableSeats($myevent->id,request('tablenr'));
        if($useable && request('tablenr') == $reservation->TableNr) $maxtableseats += $reservation->TableSeats;

        $rules = array();
        if($myevent->Tickets == -999) $rules['tickets'] = ['required','lte: ' . $ticketinfo ,'gte: ' . (getdata($this->get('tablecount'),0) +  getdata($this->get('seatnr'),0)),new ValidReserv($standinginfo,$standing,1)];
        else $rules['tickets'] = ['required','lte: ' . $ticketinfo ,'gte: ' . (getdata($this->get('tablecount'),0) +  getdata($this->get('seatnr'),0)),new ValidReserv($standinginfo,$standing,1)];
        if(request('customRadio') == 'Yes') $rules['seatnr'] = 'required|max:2|lte: ' . $seatsinfo;
        if(request('inlineDefaultRadiosExample') == 'Yes') $rules['tablecount'] = ['required','lte: ' . $tablesinfo,new ValidReserv($maxtableseats,request('tablecount'),2)];
        

        

        return $rules;
    }
}
