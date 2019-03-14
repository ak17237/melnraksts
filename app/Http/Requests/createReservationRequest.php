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
        $myevent = Events::find($this->route('id')); // saņemam vajadzīgo id lai atrast vajadzīgo pasākumu
        $data = reservinfo($this->route('id')); // funkcija kura ir helpers.php failā un kura atgriež datus par atlikušajām vietām
        
        $ticketinfo = $data[0]; // (0 = biļešu skaits,1 = sēdvietu skaits,2 = galdu skaits,3 = stāvvietu skaits)
        $seatsinfo = $data[1];
        $tablesinfo = $data[2];
        $standinginfo = $data[3];

        $standing = getdata($this->get('ticketcount'),0) - (getdata($this->get('tablenr'),0) * $myevent->Seatsontablenumber + getdata($this->get('seatnr'),0)); // izvēlēto stāvvietu skaits

        $rules = array();
        if($myevent->Tickets == -999) $rules['ticketcount'] = ['required','gte: ' . (getdata($this->get('tablenr'),0) * $myevent->Seatsontablenumber +  getdata($this->get('seatnr'),0)),new ValidReserv($standinginfo,$standing)];
        else $rules['ticketcount'] = ['required','lte: ' . $ticketinfo ,'gte: ' . (getdata($this->get('tablenr'),0) * $myevent->Seatsontablenumber +  getdata($this->get('seatnr'),0)),new ValidReserv($standinginfo,$standing)];
        if(request('customRadio') == 'Yes') $rules['seatnr'] = 'required|lte: ' . $seatsinfo;
        if(request('inlineDefaultRadiosExample') == 'Yes') $rules['tablenr'] = 'required|lte: ' . $tablesinfo;
        

        

        return $rules;
    }
}
