<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Auth;

class RegisterController extends Controller
{
    public function showRegister(){

        return view('Auth.Register');
        

    }
    public function validation(Request $request){

        $rules = [
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6|max:255',
        ];

        $messages = [
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'min' => 'Minimāli pieļaujamais garums ir :min',
            'required' => ':attribute ir obligāts',
            'confirmed' => ':attribute ir jābūt vienādai',
            'password.required' => 'Parole ir obligāta',
        ];
        $attributes = [
            'fname' => 'Vārds',
            'lname' => 'Uzvārds',
            'email' => 'E-pasts',
            'password' => 'Parolei',
        ];
        return $this->validate($request, $rules,$messages,$attributes);

    }
    public function Register(Request $request){

        $role_user = Role::where('Name','User')->first();

        $this->validation($request);
// validējam laukus un ierakstam visu datubāzē
        User::create([
            'First_name' => $request['fname'],
            'Last_name' => $request['lname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user = User::where('email',$request['email'])->first();
        $user->roles()->attach($role_user); // pieškiram lietotājam parastā lietotāja tiesības

        Auth::login($user); // ielogojam
        return redirect('/');
    }
}
