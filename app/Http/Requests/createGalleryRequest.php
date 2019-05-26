<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MultipleFileName;


class createGalleryRequest extends FormRequest
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
    public function messages()
    {
        return[ // ziņas aizvietošana uz savu
            'image' => 'Failam jābūt bildes formātā(png,jpg,gif utt.)',
        ];
    }
    public function rules()
    {
        
        $rules = array();

        if(request()->hasFile('gallery')){ // ja tiek ielādei faili pārbaudīt katram savu validāciju klasē

            for($i = 0;$i < sizeof(request('gallery'));$i++){ // MultipleFileName ir klase,kurā tiek pārbaudītas validācijas,kuras laravels nevar pārbaudīt

                $rules['gallery.' . $i] = ['image',new MultipleFileName(request('gallery.' . $i)->getClientOriginalName(),2)];
// pirmais arguments ir vārds kuru pārbaudīt uz dublikātu,otrais ir režīms(1 = pdf faili,2 = attēli),trešais ir izvēleto pdf skaits,ceturtais ir pasākuma id kuram ir jāpārbauda, skaits
            }
        }
    return $rules;
    }
}
