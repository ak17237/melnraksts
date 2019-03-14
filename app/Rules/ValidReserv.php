<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Events;
use App\Reservation;

class ValidReserv implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($standinginfo,$standing)
    {
        
        $this->standmax = $standinginfo; // max pieļauto stāvvietu skaits
        $this->standcurr = $standing; // izvēlēto stāvvietu skaits
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
        if($this->standcurr <= $this->standmax || $this->standmax == "Neierobežots") return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Stavvietu skaitam jabut mazakam par ' . $this->standmax;
    }
}
