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
    public function attributes()
    {
       return[
        'fname' => 'Vārds',
        'lname' => 'Uzvārds',
        'email' => 'E-pasts',
        'oldpassword' => 'Paroles lauks',
        'password' => 'Jaunas paroles lauks',
       ];
    }
    public function messages()
    {
        return[
            'image' => ':attribute jābūt bildes formātā(png,jpg,gif utt.)',
            'required' => ':attribute nevar būt tukšs',
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'min' => 'Minimālais pieļaujamais garums ir :min',
            'confirmed' => 'Paroles apstiprināšanas kļūda: Paroles nesakrīt'

        ];
    }
    public function rules()
    {
        $rules = array();

        if(request('action') == 'fname') $rules['fname'] = 'required|max: 20';
        if(request('action') == 'lname') $rules['lname'] = 'required|max: 34';
        if(request('action') == 'email') $rules['email'] = 'required|max: 49';
        if(request('action') == 'pass') {

            $rules['password'] = 'required|min:6|confirmed|max:255';
            $rules['oldpassword'] = 'required|min:6|max:255';

        }
        if(request('avatar') != NULL)
            $rules['avatar'] = 'image';


        return $rules;   
    }

}
