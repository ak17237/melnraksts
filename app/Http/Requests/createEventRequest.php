<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ImageName;
use App\Rules\ValidReserv;
use App\Events;

class createEventRequest extends FormRequest
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
    public function attributes()
    {
       return[
        'title' => 'Nosaukums',
        'datefrom' => 'Datums',
        'dateto' => 'Datums',
        'address' => 'Adrese',
        'anotation' => 'Anotācijas lauks',
        'ticketcount' => 'Biļešu skaits',
        'seatnr' => 'Sēdvietu skaits',
        'tablenr' => 'Galdu skaits',
        'seatsontablenr' => 'Sēdvietu skaits pie galda',
        'file' => 'Failam',
       ];
    }
    public function messages()
    {
        return[
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'required' => ':attribute ir obligāts',
            'image' => ':attribute jābūt bildes formātā(png,jpg,gif utt.)',
            'gte' => ':attribute jābūt lielāks vai vienāds par :value',
        ];
    }
    public function rules() // pasākumu saglabāšanas noteikumi ja tika nospiesta poga create
    {
        $rules = array();
        if(request('action') == 'create') $rules['title'] = 'required|max:250';
        if(request('action') == 'create') $rules['datefrom'] = 'required';
        if(request('action') == 'create') $rules['dateto'] = 'required';
        if(request('action') == "create") $rules['address'] = 'required|max:500';
        if(request('action') == "create") $rules['anotation'] = 'required|max:500';
        if(request('action') == "create" && request('Radio') == 'Yes') $rules['ticketcount'] = 'required';
        if(request('action') == "create" && request('customRadio') == 'Yes') $rules['seatnr'] = ['required'];
        if(request('action') == "create" && request('inlineDefaultRadiosExample') == 'Yes') {
            $rules['tablenr'] = ['required'];
            $rules['seatsontablenr'] = ['required'];
        }

        if(request('file') != NULL)
            $rules['file'] = ['image',new ImageName(request('file')->getClientOriginalName())];
        else
            $rules['file'] = 'image';

        if(request('action') == "create" && request('Radio') == 'Yes' && request('customRadio') == 'Yes' && request('inlineDefaultRadiosExample') == 'Yes')
            $rules['ticketcount'] = ['required','gte:' . (getdata($this->get('tablenr'),0) * getdata($this->get('seatsontablenr'),0) + getdata($this->get('seatnr'),0))];
        else if(request('action') == "create" && request('Radio') == 'Yes' && request('customRadio') == 'Yes')
            $rules['ticketcount'] = ['required','gte:' . getdata($this->get('seatnr'),0)];
        else if(request('action') == "create" && request('Radio') == 'Yes' && request('inlineDefaultRadiosExample') == 'Yes')
            $rules['ticketcount'] = ['required','gte:' . getdata($this->get('tablenr'),0) * getdata($this->get('seatsontablenr'),0)];
        else $rules['ticketcount'] = array(); // lai strādātu metode array_push,ja nav galdu un sēdvietu,tad šis nebūs masīvs un nestrādāš array_push
        if($this->route()->getName() == 'edit'){

            $data = resrvcount($this->route('id'));

            if(request('ticketcount') != NULL) array_push($rules['ticketcount'],new ValidReserv(request('ticketcount'),$data[0],3));
            if(request('seatnr') == NULL) $rules['customRadio'] = new ValidReserv(getdata(request('seatnr'),0),$data[1],5);
            elseif(request('seatnr') != NULL) array_push($rules['seatnr'],new ValidReserv(getdata(request('seatnr'),0),$data[1],4));
            if(request('tablenr') == NULL) $rules['inlineDefaultRadiosExample'] = new ValidReserv(getdata(request('tablenr'),0) * getdata(request('seatsontablenr'),0),$data[2],7);
            elseif(request('tablenr') != NULL) {

                array_push($rules['tablenr'],new ValidReserv(getdata(request('tablenr'),0) * getdata(request('seatsontablenr'),0),$data[2],6));
                array_push($rules['seatsontablenr'],new ValidReserv(getdata(request('seatsontablenr'),0),$data[3],8));
                

            }

    
        }
        return $rules;
    }
}
