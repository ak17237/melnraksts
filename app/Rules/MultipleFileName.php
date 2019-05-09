<?php

namespace App\Rules;
use App\Pdf;

use Illuminate\Contracts\Validation\Rule;

class MultipleFileName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name,$type,$selectedpdf,$id = NULL)
    {
        $this->name = $name;
        $this->type = $type;
        $this->validation = true;
        $this->eventid = $id;
        $this->selectedpdf = $selectedpdf;
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

            $pdf = Pdf::all();

            foreach($pdf as $p){

                    if($p->Name === $this->name) {

                        $this->validation = false;

                    }
            }
            if($this->eventid != NULL){

            $pdf = Pdf::where('Event_ID',$this->eventid)->get();
            
            $this->pdfcount = sizeof($pdf) + $this->selectedpdf;
            }
            else $this->pdfcount = $this->selectedpdf;

            if($this->pdfcount > 5) $this->validation = false;

            if($this->validation) return true;

        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->type == 1){

            if($this->pdfcount > 5) 
                return 'Maksimālais pdf pielikumu skaits vienam pasākumam(5) ir pārsniegts'; 
            return 'Fails ' . $this->name . ' jau eksistē,nomainiet lūdzu nosaukumu';

        }
    }
}
