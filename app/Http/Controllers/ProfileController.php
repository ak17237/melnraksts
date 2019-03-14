<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){ // izvada ielogotā lietotāja datus

        $First_name = User::getFirstname();
        $Last_name = User::getLastname();
        $Email = User::getEmail();

        return view('Profile.Profile',compact('First_name','Last_name','Email'));

    }
    public function changename(Request $request){ // paņem ielogotā lietotāja datus un maina tos ar ievadītiem

        $this->validate($request, [
            'fname' => 'required',
         ]);
         $First_name = User::where('email', Auth::user()->email)->first();
         $First_name->First_name = $request->get('fname');
         $First_name->save();
         return redirect()->back()->with('fname','First name changed succesfully');  

    }
    public function changesurname(Request $request){

        $this->validate($request, [
            'lname' => 'required',
         ]);
         $Last_name = User::where('email', Auth::user()->email)->first();
         $Last_name->Last_name = $request->get('lname');
         $Last_name->save();
         return redirect()->back()->with('lname','Last name changed succesfully');

    }
    public function changeemail(Request $request){

        $this->validate($request, [
            'email' => 'required',
         ]);
         $email = User::where('email', Auth::user()->email)->first();
         $email->email = $request->get('email');
         $email->save();
         return redirect()->back()->with('email','Email changed succesfully');

    }
    public function changepass(){

        return view('Profile.passchange');

    }
    public function changepassword(Request $request){

        $this->validate($request, [
            'password' => 'required|min:6|confirmed|max:255',
            'oldpassword' => 'required|min:6|max:255',

        ]); // pārbauda eksistējošo paroli
        $password = User::where('email', Auth::user()->email)->first()->password;
        if (Hash::check($request['oldpassword'],$password)){

            $user = User::where('email', Auth::user()->email)->first();
            $user->password = Hash::make($request['password']); // ieraksta jaunu paroli
            $user->save();
            return redirect()->route('profile.index')->with('message','Your password was succesfully changed');
        }
        else return redirect()->back()->withInput($request->input())->withErrors(['oldpassword' => 'Wrong password']);

    }
}
