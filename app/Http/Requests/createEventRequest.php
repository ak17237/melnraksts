<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ImageName;
use App\Rules\ValidReserv;
use App\Rules\MultipleFileName;
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
       return[  // atribūtu nosaukumu pārveidošana
        'title' => 'Nosaukums',
        'datefrom' => 'Datums',
        'dateto' => 'Datums',
        'address' => 'Adreses lauks',
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
        return[ // kļūdu ziņu definēšana
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'min' => 'Minimālais pieļaujamais garums ir :min',
            'required' => ':attribute ir obligāts',
            'image' => ':attribute jābūt bildes formātā(png,jpg,gif utt.)',
            'gte' => ':attribute jābūt lielāks vai vienāds par :value',
            'mimes' => 'Pielikumiem jābūt :values tipa failam.'
        ];
    }
    public function rules() // pasākumu saglabāšanas noteikumi ja tika nospiesta poga create
    {
        $rules = array();
        if(request('action') == 'create') $rules['title'] = 'required|max:250|min:4'; // ja ir nospiests create tad sekojoši noteikumi
        if(request('action') == 'create') $rules['datefrom'] = 'required';
        if(request('action') == 'create') $rules['dateto'] = 'required';
        if(request('action') == "create") $rules['address'] = 'required|max:500|min:4';
        if(request('action') == "create") $rules['anotation'] = 'required|max:500|min:4';
        if(request('action') == "create" && request('Radio') == 'Yes') $rules['ticketcount'] = ['required']; // ja izvelnes nav deaktivētas tad required
        if(request('action') == "create" && request('customRadio') == 'Yes') $rules['seatnr'] = ['required'];
        if(request('action') == "create" && request('inlineDefaultRadiosExample') == 'Yes') {
            $rules['tablenr'] = ['required'];
            $rules['seatsontablenr'] = ['required'];
        }
        if(request('file') != NULL) // ja fails ir ielādēts,tad viņam jābūt attēla formātā un nosaukumam nav jābūt jau ielādētam agrāk,kas tiek pārbaudīts ImageName klasē
            $rules['file'] = ['image',new ImageName(request('file')->getClientOriginalName())]; // tiek padots faila nosaukums

            // pārbauda lai biļešu skaits(ja ir izvēlēts ierobežots) nebūtu mazāks par sēdvietu un galdu sēdvietu summu,
            // ja tie bija atzīmēti,pārbaudam visus iespējamos radio izvēlnes variantus getdata() funkcija saņem vērtību un ja tā ir tukša atgriež otro parametru
        if(request('action') == "create" && request('Radio') == 'Yes' && request('customRadio') == 'Yes' && request('inlineDefaultRadiosExample') == 'Yes')
            $rules['ticketcount'] = ['required','gte:' . (getdata($this->get('tablenr'),0) * getdata($this->get('seatsontablenr'),0) + getdata($this->get('seatnr'),0))];
        else if(request('action') == "create" && request('Radio') == 'Yes' && request('customRadio') == 'Yes')
            $rules['ticketcount'] = ['required','gte:' . getdata($this->get('seatnr'),0)];
        else if(request('action') == "create" && request('Radio') == 'Yes' && request('inlineDefaultRadiosExample') == 'Yes')
            $rules['ticketcount'] = ['required','gte:' . getdata($this->get('tablenr'),0) * getdata($this->get('seatsontablenr'),0)];

        if($this->route()->getName() == 'edit'){ // ja mēs atnācām no pasākuma rediģēšanas lapas

            $data = resrvcount($this->route('id'));

            // skaita ierobežošana biļetēm sēdvietām galdeim būs veikta ValidReserv klasē,padodot tai,biļešu skaitu un tipu
            // Pdod,biļešu skait,sēdvietu skaitu,sēdvietu pie galdeim skaitu,pārbaudot tos ar jau rezerevēto skaitu
            if(request('ticketcount') != NULL) array_push($rules['ticketcount'],new ValidReserv(request('ticketcount'),$data[0],3));
            if(request('seatnr') == NULL) $rules['customRadio'] = new ValidReserv(getdata(request('seatnr'),0),$data[1],5);
            elseif(request('seatnr') != NULL) array_push($rules['seatnr'],new ValidReserv(getdata(request('seatnr'),0),$data[1],4));
            if(request('tablenr') == NULL) $rules['inlineDefaultRadiosExample'] = new ValidReserv(getdata(request('tablenr'),0) * getdata(request('seatsontablenr'),0),$data[2],7);
            elseif(request('tablenr') != NULL) {

                array_push($rules['tablenr'],new ValidReserv(getdata(request('tablenr'),0) * getdata(request('seatsontablenr'),0),$data[2],6));
                array_push($rules['seatsontablenr'],new ValidReserv(getdata(request('seatsontablenr'),0),$data[3],8));
                

            }
// PDF failu validācija
            if(request()->hasFile('pdffile')){ // ja pdf faili tika ivēlēti

                for($i = 0;$i < sizeof(request('pdffile'));$i++){ // MultipleFileName ir klase,kurā tiek pārbaudītas validācijas,kuras laravels nevar pārbaudīt
                // katram failam atseviška validācija,PDF formāts un Klases validācija,saņem pdf nosaukumu,tipu,pdf kopējo skaitu,pasākuma ID
                    $rules['pdffile.' . $i] = ['mimes:pdf',new MultipleFileName(request('pdffile.' . $i)->getClientOriginalName(),1,sizeof(request('pdffile')),$this->route('id'))];
// pirmais arguments ir vārds kuru pārbaudīt uz dublikātu,otrais ir režīms(1 = pdf faili,2 = attēli),trešais ir izvēleto pdf skaits,ceturtais ir pasākuma id kuram ir jāpārbauda, skaits
                } 
            }
    
        }
        else { // PDF failu validāciju izveidojot pasākumu

            if(request()->hasFile('pdffile')){

                for($i = 0;$i < sizeof(request('pdffile'));$i++){ // MultipleFileName ir klase,kurā tiek pārbaudītas validācijas,kuras laravels nevar pārbaudīt
// tas pats kā augšā tikai bez pasākuma ID jo mēs zinam ka pasākumam nav ielādēto failu,jo tas tikai tagad tiek izveidots
                    $rules['pdffile.' . $i] = ['mimes:pdf',new MultipleFileName(request('pdffile.' . $i)->getClientOriginalName(),1,sizeof(request('pdffile')))];
// pirmais arguments ir vārds kuru pārbaudīt uz dublikātu,otrais ir režīms(1 = pdf faili,2 = attēli),trešais ir izvēleto pdf skaits,ceturtais ir pasākuma id kuram ir jāpārbauda, skaits
                } 
            }

        }
        return $rules;
    }
}
