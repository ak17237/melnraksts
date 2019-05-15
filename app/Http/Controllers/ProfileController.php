<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\VerifyEmail;
use App\Resetuser;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\createProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){ // izvada ielogotā lietotāja datus

        $First_name = User::getFirstname();
        $Last_name = User::getLastname();
        $Email = User::getEmail();

        return view('Profile.Profile',compact('First_name','Last_name','Email'));

    }
    public function changeavatar(createProfileRequest $request){

        $user = User::where('email', Auth::user()->email)->first();

        if(Storage::disk('avatar')->has(Auth::user()->Avatar)) 
            Storage::disk('avatar')->delete(Auth::user()->Avatar);

        $file = $request['avatar'];
        if($file){
            Storage::disk('avatar')->put($request['avatar']->getClientOriginalName(),File::get($file));
        }

        $user->fill([
            'Avatar' => $request['avatar']->getClientOriginalName(),
        ]);
        $user->save();

        return redirect()->route('profile.index')->with('message','Jūsu profila bilde tika veiksmīgi izmainīta');

    }
    public function changename(createProfileRequest $request){ // paņem ielogotā lietotāja datus un maina tos ar ievadītiem

         $First_name = User::where('email', Auth::user()->email)->first();
         $First_name->First_name = $request->get('fname');
         $First_name->save();
         return redirect()->back()->with('message','Jūsu vārds tika veiksmīgi izmainīts');  

    }
    public function changesurname(createProfileRequest $request){

         $Last_name = User::where('email', Auth::user()->email)->first();
         $Last_name->Last_name = $request->get('lname');
         $Last_name->save();
         return redirect()->back()->with('message','Jūsu uzvārds tika veiksmīgi izmainīts');

    }
    public function changeemail(createProfileRequest $request){

         $email = User::where('email', Auth::user()->email)->first();
         $email->email = $request->get('email');
         $email->save();
         return redirect()->back()->with('message','Jūsu e-pasts tika veiksmīgi izmainīts');

    }
    public function changepassword(createProfileRequest $request){

        $password = User::where('email', Auth::user()->email)->first()->password;
        if (Hash::check($request['oldpassword'],$password)){

            $user = User::where('email', Auth::user()->email)->first();
            $user->password = Hash::make($request['password']); // ieraksta jaunu paroli
            $user->save();
            return redirect()->route('profile.index')->with('message','Jūsu parole tika veiksmīgi izmainīta');
        }
        else return redirect()->back()->withInput($request->input())->withErrors(['oldpassword' => 'Nepareiza parole']);

    }
    public function Reset(){

        $user = User::where('email',Auth::user()->email)->first();
        $resetuser = Resetuser::where('id',$user->id)->first();

        $user->fill([
            'email' => $resetuser->email,
            'password' => $resetuser->password,
        ]);
        $user->save();

        return redirect()->back()->with('message','Jūsu e-pasts un parole tika veiksmīgi atjaunoti!');

    }
}
