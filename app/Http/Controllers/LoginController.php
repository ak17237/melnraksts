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
    public function showLogin(){ // ielogošanās skats
 
        return view('Auth.Login');

    }
    public function Login(Request $request){ // ielogošanāš pārbaude

        $rules = [
            'email' => 'required|email|max:34', // atribūtu noteikumi
            'password' => 'required|min:6|max:50',
        ];
        $messages = [ // latviskots nosaukums paiziņojumiem noteikumiem
            'max' => 'Māksimāls pieļaujamais garums ir :max',
            'min' => 'Minimāli pieļaujamais garums ir :min',
            'required' => ':attribute ir obligāts',
            'password.required' => 'Parole ir obligāta',
            'email.email' => 'E-pastam jābūt dērīgam',
        ];
        $attributes = [ // atribūtu nosaukums
            'email' => 'E-pasts',
            'password' => 'Parole',
        ];
        
        $this->validate($request,$rules,$messages,$attributes); // validējam laukus

    if(!empty($request['remember'])) $remember = true; // remember me checkbox pārbaude
    else $remember = false;

    if(!empty($request['resetuser'])) $resetuser = true; // ielogošanāš tipa pārbaude
    else $resetuser = false;

    if($remember) { // ja bija remember me,tad izveidojam cookies ar ievadītiem datiem

        $cookie_email = cookie('email', $request['email'], 60 * 24 * 30);
        $cookie_password = cookie('password', $request['password'], 60 * 24 * 30);

    }
    else { // ja nē tad izveidojam nederīgus

        $cookie_email = cookie('email','',-1);
        $cookie_password = cookie('password','',-1); 

    }
        
    if($resetuser) { // ja bija ielogoties ar vēsturieskiem datiem
        
        $email = Resetuser::where('email', $request['email'])->first(); // saņemam datus no nemainīgās tabulas
        $guard = 'resetuser';

    }
    else{ // ja parasta logošanāš tad no parastās lietotāju tabulas
        
        $email = User::where('email', $request['email'])->first(); // Pieprasītā epasta paņemšana no datubāzes
        $guard = 'user';

    }
        if($email){ // ja lietotājs ar tādu e-pastu no izvēlētās tabulas eskistē

            $password = $email->password; // Ja tāds eksistē pārbaudam to paroli ar ievadīto

            if (Hash::check($request['password'],$password)){ // ja sakrīt ielogojam
                
                Auth::guard($guard)->attempt(['email' => $request['email'], 'password' => $request['password']],$remember); 
                Auth::login(User::where('id', $email->id)->first()); // ielogojam tieši lietotāju no parastās tabulas
                return redirect('/')->cookie($cookie_email)->cookie($cookie_password)->cookie('login','logged in',60 * 24 * 30);

            } // ja nesakrīt paroles izvadam kļūdu
            else return redirect()->back()->withInput($request->input())->withErrors(['password' => 'Nepareiza parole']); // ja nē kļūda
        } // ja lietotājs pēc e-pasta nebija atrasts izvadam kļūdu
        else return redirect()->back()->withInput($request->input())->withErrors(['email' => 'Nepareizs e-pasts']); // ja epasts $email ir tukšs objekts tad tāds epasts nav reģistrēts
    }
}