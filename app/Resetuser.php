<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Resetuser as Authenticatable;


class Resetuser extends Authenticatable
{
    protected $fillable = [
        'First_name','Last_name','email', 'password','Avatar',
    ];
   /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $guard = 'resetuser';
    
    public function getAuthPassword () {

        return $this->password;
    
    }
}
