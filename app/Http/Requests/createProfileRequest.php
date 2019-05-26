<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class createProfileRequest extends FormRequest
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
    public function attributes() // atribūtu savi nosaukumi
    {
       return[ 
        'fname' => 'Vārds',
        'lname' => 'Uzvārds',
        'email' => 'E-pasts',
        'oldpassword' => 'Paroles lauks',
        'password' => 'Jaunas paroles lauks',
        'buttontitle' => 'Pogas virsraksts',
        'buttonlink' => 'Pogas links',
        'reciever' => 'Saņēmēju lauks',
        'emailtitle' => 'Ziņas virsrsksts',
        'emailtext' => 'Ziņas teksts',
        'transport' => 'Pasākumu lauks'
       ];
    }
    public function messages() // ziņu savi nosaukumi
    {
        return[
            'image' => ':attribute jābūt bildes formātā(png,jpg,gif utt.)',
            'required' => ':attribute nevar būt tukšs',
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'min' => 'Minimālais pieļaujamais garums ir :min',
            'confirmed' => 'Paroles apstiprināšanas kļūda: Paroles nesakrīt',
            'email.email' => 'E-pastam jābūt dērīgam'

        ];
    }
    public function rules()
    {
        $rules = array();
 // ja profilā ir izvēlēts izmainīt noteikto lauku,tad tas ir obligāts ar savu MAX vērtību
        if(request('action') == 'fname') $rules['fname'] = 'required|max: 20';
        if(request('action') == 'lname') $rules['lname'] = 'required|max: 34';
        if(request('action') == 'email') $rules['email'] = 'required|email|max: 49';
        if(request('action') == 'pass') { // ja pase,tad vecai un jaunajai šādi noteikumi

            $rules['password'] = 'required|min:6|confirmed|max:50';
            $rules['oldpassword'] = 'required|min:6|max:50';

        }
        if(request('avatar') != NULL) // ja tika izvēlēta bilde
            $rules['avatar'] = 'image'; // bildes formāts

        if(request('action') == 'send' || request('action') == 'preview'){ // ja ir e-pastu sūtīšana

            if(request('inlineDefaultRadiosExample') == 'Yes') { // pārbaudīt vai visi pogas lauki aizpildīti gadījumā kad izvēlne jā

                $rules['buttontitle'] = ['required'];
                $rules['buttonlink'] = ['required'];

            }
           // e-pasta virsraksts un teksts obligāti
            $rules['emailtitle'] = ['required'];
            $rules['emailtext'] = ['required'];
            // ja transporta sūtīšana pasākuma izvēlne obligāta,ja lietotājiem,tad vismaz viens lietotājs no izvēlnes
            if(request('transportcb') == "on") $rules['transport'] = ['required'];
            else  $rules['reciever'] = ['required'];

        }
        


        return $rules;   
    }

}
