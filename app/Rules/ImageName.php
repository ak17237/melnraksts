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
    public function __construct($currentName,$type = NULL)
    {
        $this->currentName = $currentName;

        $events = Events::all();
        $users = User::all();
        $this->validation = true;
        $this->namevalid = true; 
        $this->type = $type;
        if($this->type == NULL){

            foreach($events as $e){

                if($this->currentName === $e->imgextension) $this->validation = false; 
        
            }
        }
        if(strlen($this->currentName) > 50) { 
            $this->namevalid = false; 
            $this->validation = false; 
        }
        if($this->type == 1){

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
    public function passes($attribute, $value)
    {
        if($this->validation) return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->namevalid == true)
            return 'Attēls ar šādu nosaukumu jau eksistē';
        else
            return 'Attēla nosaukums pārsniedz 50 simbolus';
    }
}
