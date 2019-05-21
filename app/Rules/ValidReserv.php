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
    public function __construct($max,$current,$type)
    {

        $this->max = $max; // max pieļauto stāvvietu/sēdvietu skaits
        $this->curr = $current; // izvēlēto stāvvietu/sēdvietu skaits
        $this->type = $type;

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
        if($this->curr <= $this->max || $this->max === "Neierobežots") return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->type == 1)
        return 'Stavvietu skaitam jabut mazakam par ' . $this->max;
        if($this->type == 2)
        return 'Šajā galdiņa nepietiek vietas';
        if($this->type == 3)
        return "Rezervētās biļetes(" . $this->curr . ") ir vairāk par jūsu vēlamajām(" . $this->max . ")";
        if($this->type == 4)
        return "Rezervētās sēdvietas(" . $this->curr . ") ir vairāk par jūsu vēlamajām(" . $this->max . ")";
        if($this->type == 5)
        return "Nevar noņemt sēdvietas,kad tās jau ir rezervētas (" . $this->curr . ")";
        if($this->type == 6)
        return "Rezervētās sēdvietas pie galdiem(" . $this->curr . ") ir vairāk par jūsu vēlamajām(" . $this->max . ")";
        if($this->type == 7)
        return "Nevar noņemt galdus,kad tos jau rezervēja (" . $this->curr . ")";
        if($this->type == 8)
        return "Nevar mainīt sēdvietu skaitu pie galdiem,kad vismaz pie viena galda jau sēž(" . $this->curr . ") vairāk par jūsu vēlamo skaitu (" . $this->max . ")";
    }
}
