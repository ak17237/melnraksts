<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Resetuser;

class LoginController extends Controller
{
    public function showLogin(){
 
        return view('Auth.Login');

    }
    public function Login(Request $request){ 

        $rules = [
            'email' => 'required|email|max:34',
            'password' => 'required|min:6|max:50',
        ];
        $messages = [
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'min' => 'Minimāli pieļaujamais garums ir :min',
            'required' => ':attribute ir obligāts',
            'password.required' => 'Parole ir obligāta',
            'email.email' => 'E-pastam jābūt dērīgam',
        ];
        $attributes = [
            'email' => 'E-pasts',
            'password' => 'Parole',
        ];
        
        $this->validate($request,$rules,$messages,$attributes);
    if(!empty($request['remember'])) $remember = true;
    else $remember = false;

    if(!empty($request['resetuser'])) $resetuser = true;
    else $resetuser = false;

    if($remember) {

        $cookie_email = cookie('email', $request['email'], 60 * 24 * 30);
        $cookie_password = cookie('password', $request['password'], 60 * 24 * 30);

    }
    else {

        $cookie_email = cookie('email','',-1);
        $cookie_password = cookie('password','',-1); 

    }
        
    if($resetuser) {
        
        $email = Resetuser::where('email', $request['email'])->first();
        $guard = 'resetuser';

    }
    else{
        
        $email = User::where('email', $request['email'])->first(); // Pieprasītā epasta paņemšana no datubāzes
        $guard = 'user';

    }
        if($email){ 

            $password = $email->password; // Ja tāds eksistē pārbaudam to paroli ar ievadīto

            if (Hash::check($request['password'],$password)){ // ja sakrīt ielogojam
                
                Auth::guard($guard)->attempt(['email' => $request['email'], 'password' => $request['password']],$remember);
                Auth::login(User::where('id', $email->id)->first()); 
                return redirect('/')->cookie($cookie_email)->cookie($cookie_password)->cookie('login','logged in',60 * 24 * 30);

            }
            else return redirect()->back()->withInput($request->input())->withErrors(['password' => 'Nepareiza parole']); // ja nē kļūda
        }
        else return redirect()->back()->withInput($request->input())->withErrors(['email' => 'Nepareizs e-pasts']); // ja epasts $email ir tukšs objekts tad tāds epasts nav reģistrēts
    }
}