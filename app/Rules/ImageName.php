<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Events;

class ImageName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currentName)
    {
        $this->currentName = $currentName;

        $events = Events::all();
        $this->validation = true;

        foreach($events as $e){

            if($this->currentName === $e->imgextension) $this->validation = false; 
    
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
            return 'Attēls ar šādu nosaukumu jau eksistē';
    }
}
