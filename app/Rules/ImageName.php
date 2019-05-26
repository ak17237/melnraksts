<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Events;
use App\User;

class ImageName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currentName,$type = NULL) // saņem faila vārdu
    {
        $this->currentName = $currentName;

        $events = Events::all(); // saņem visus pasākumus un lietotājus
        $users = User::all();
        $this->validation = true; // validācijas statuss
        $this->namevalid = true;  // vārda validācijas statuss
        $this->type = $type; // tips,ko tieši pārbaudīt pasākuma attēlu jeb bildi
        if($this->type == NULL){ // ja tips nav norādīts

            foreach($events as $e){ // pārbaudam vai nav kādam pasākumam tāds nosaukums

                if($this->currentName === $e->imgextension) $this->validation = false; 
        
            }
        } // pārbaudam lai nosaukuma garums nebūtu lielāsk par 50 simboliem
        if(strlen($this->currentName) > 50) { 
            $this->namevalid = false; 
            $this->validation = false; 
        } // ja tips ir 1 tad pārbaudam bildi
        if($this->type == 1){ // vai nav kādam lietotājam jau ielādēts attēls ar šādu nosaukumu

            foreach($users as $u){

                if($this->currentName === $u->Avatar) $this->validation = false; 
        
            }

        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) //
    {
        if($this->validation) return true; // ja validācijas kļūdas nebija,tad atgriezt pozitīvu rezultātu
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() // ja pozitīva rezultāta nav izzsauc šo funkciju
    {
        if($this->namevalid == true) // ja kļūda bija nosaukuma dublikācijā izvada kļūdu
            return 'Attēls ar šādu nosaukumu jau eksistē';
        else // ja nē,tad kļūda bija nosaukuma garumā
            return 'Attēla nosaukums pārsniedz 50 simbolus';
    }
}
