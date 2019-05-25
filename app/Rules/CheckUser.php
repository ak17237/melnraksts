<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class CheckUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct($email,$eventid)
    {
        $this->email = $email; // lietotāja e-pasts
        $this->eventid = $eventid; // pasākuma id
        $this->validateuser = false; // lietotāja validācijas
        $this->validatecount = false; // lietotāja rezervāciju sakita validācija
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
        if(User::where('email', $this->email)->exists()) { // Pārbauda vai eksistē lietotājs ar šādu e-pastu
            
            $this->validateuser = true;   
            if(checkResrvationCount($this->eventid,$this->email) < 2) $this->validatecount = true;
            /* Pārbaudīt vai lietotājs,kuru vajag rezervēt jau rezervēja maksimālo biļešu skaitu */

        }
        if($this->validateuser == true && $this->validatecount == true) return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->validateuser == false)
            return 'Lietotājs ar šādu e-pastu nav reģistrēts';
        elseif($this->validatecount == false)
            return 'Šim lietotājam jau ir maskimālais rezervāciju skaits(2)';
    }
}
