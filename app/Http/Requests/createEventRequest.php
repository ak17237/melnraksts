<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        if(request('action') == "create" && request('customRadio') == 'Yes') $rules['seatnr'] = 'required';
        if(request('action') == "create" && request('inlineDefaultRadiosExample') == 'Yes') {
            $rules['tablenr'] = 'required';
            $rules['seatsontablenr'] = 'required';
        }
        $rules['file'] = 'image';
        if(request('action') == "create" && request('Radio') == 'Yes' && request('customRadio') == 'Yes' && request('inlineDefaultRadiosExample') == 'Yes')
            $rules['ticketcount'] = 'required|gte:' . (getdata($this->get('tablenr'),0) * getdata($this->get('seatsontablenr'),0) + getdata($this->get('seatnr'),0));
        else if(request('action') == "create" && request('Radio') == 'Yes' && request('customRadio') == 'Yes')
            $rules['ticketcount'] = 'required|gte:' . getdata($this->get('seatnr'),0);
        else if(request('action') == "create" && request('Radio') == 'Yes' && request('inlineDefaultRadiosExample') == 'Yes')
            $rules['ticketcount'] = 'required|gte:' . getdata($this->get('tablenr'),0) * getdata($this->get('seatsontablenr'),0);
        return $rules;
    }
}
