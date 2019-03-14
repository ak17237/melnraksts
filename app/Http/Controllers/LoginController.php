<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\User;

class LoginController extends Controller
{
    public function showLogin(){

        return view('Auth.Login');

    }
    public function Login(Request $request){ 

        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:255',
        ]);
        $email = User::where('email', $request['email'])->first(); // Pieprasītā epasta paņemšana no datubāzes

        if($email){ $password = User::where('email', $request['email'])->first()->password; // Ja tāds eksistē pārbaudam to paroli ar ievadīto
            if (Hash::check($request['password'],$password)){ // ja sakrīt ielogojam
            Auth::login($email);
            return redirect('/');
            }
            else return redirect()->back()->withInput($request->input())->withErrors(['password' => 'Wrong password']); // ja nē kļūda
        }
        else return redirect()->back()->withInput($request->input())->withErrors(['email' => 'Wrong email']); // ja epasts $email ir tukšs objekts tad tāds epasts nav reģistrēts
    }
}