<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    public function getAuthPassword () {

        return $this->password;
    
    }
    public function roles(){
        return $this->belongsToMany('App\Role','user_role','user_id','role_id');
    }
    public function hasAnyRole($roles){ // Arguments $roles kurš sastāves no tiesības kuras mēs gribam pārbaudei
        if(is_array($roles)){ // ja ir masīvs pārbauda vai lietotājam ir viena no šim tiesibām
            foreach($roles as $role){
                if($this->hasRole($role)){ // Pārbauda vai lietotājam ir šīs tiesības
                return true;
                }
            }    
        } else {
            if($this->hasRole($role)){ // Pārbauda vai lietotājam ir šī tiesība
                return true;
                }
        }   
        return false;
    }
    public function hasRole($role){
        if($this->roles()->where('Name',$role)->first()){ // Saņem piekļuvi pie tiesībām sī lietotāja un redz vai šajās tiesībās lietotājs ir piešķirts,tiesība kuru mēs pārbaudam parādās
            return true;
        }
    return false;
    }
    public static function getFirstname(){ // saņem ielogotā lietotāja datus
        return static::where('email',Auth::user()->email)->first()->First_name;
    }
    public static function getLastname(){
        return static::where('email',Auth::user()->email)->first()->Last_name;
    }
    public static function getEmail(){
        return static::where('email',Auth::user()->email)->first()->email;
    }
}
