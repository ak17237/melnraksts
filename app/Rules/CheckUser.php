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
    public function __construct($email)
    {
        $this->email = $email;
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
        if(User::where('email', $this->email)->exists()) return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Lietotājs ar šādu e-pastu nav reģistrēts';
    }
}
