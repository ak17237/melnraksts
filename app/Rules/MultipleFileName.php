<?php

namespace App\Rules;
use App\Pdf;
use App\Gallery;

use Illuminate\Contracts\Validation\Rule;

class MultipleFileName implements Rule // validācijas pārbaude failu nosaukimiem ja ir padoti daudzi
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name,$type,$selectedfiles = NULL,$id = NULL)
    {
        $this->name = $name; // faila nosaukums
        $this->type = $type; // ziņas tips
        $this->validation = true; // validācijas status
        $this->eventid = $id; // pasākums kuram pārbaudīt
        $this->selectedfiles = $selectedfiles; // izvēlēto failu skaits
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->type == 1){ // type = 1 ir pdf failu apstrāde,type = 2 foto jeb galerijas failu apstrāde

            $pdf = Pdf::all(); // saņem visus pdf

            foreach($pdf as $p){ //katram pdf pārbauda:

                    if($p->Name === $this->name) { // ja tā nosaukums sakrīt ar pārbaudāmo validācijas kļūda

                        $this->validation = false;

                    }
            }
            if($this->eventid != NULL){ // ja atnāca no pasākuma izveides lapas nebūs ID

            $pdf = Pdf::where('Event_ID',$this->eventid)->get(); // saņem visus pdf failus šim pasākumsm
            // saskaitam jau eksistējošus + izvēlētos
            $this->pdfcount = sizeof($pdf) + $this->selectedfiles;
            } // ja izveides lapa,tad saņemam izvvēlēto failu skaitu
            else $this->pdfcount = $this->selectedfiles;
            // ja kopējais failu skaits ir lielāks par 5 kļūda
            if($this->pdfcount > 5) $this->validation = false;

        }
        elseif($this->type == 2){ // galerijas apstrāde
            

            $gallery = Gallery::all(); // saņemam visus galerijas failu nosaukumus

            foreach($gallery as $g){ // katram nosaukumam pārbaudam ar izvēlēto faila nosaukumu

                if($g->Name === $this->name) {

                    $this->validation = false;

                }
        }
        }
        if($this->validation) return true; // validācijas pārbaude

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->type == 1){ // ja ir PDF validācijas

            if($this->pdfcount > 5) // ja pdf kopskaits ir lielāks par 5,ja nē tad faila dublikācijas kļūda
                return 'Maksimālais pdf pielikumu skaits vienam pasākumam(5) ir pārsniegts'; 
            return 'Fails ' . $this->name . ' jau eksistē,nomainiet lūdzu nosaukumu';

        }
        if($this->type == 2){ // ja galerijas pārbaude,tad tikai dublikātu kļūda iespējama
            

            return 'Fails ' . $this->name . ' jau eksistē,nomainiet lūdzu nosaukumu';

        }
    }
}
