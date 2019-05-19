<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use Mail;
use App\Events;
use App\Reservation;
use App\VerifyEmail;
use App\Resetuser;
use App\Mail\CustomEmail;
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
        $user = User::all();
        $events = Events::whereDate('Datefrom', '>', date("Y-m-d"))->get();
        $count = 0;
        $transport = array();
        $eventid = array();

        foreach($events as $e){

            $reservation = Reservation::where('EventID',$e->id)->get();
            
            foreach ($reservation as $r){

                if($r->Transport != "Patstāvīgi") $count++; 

            }
            if($count > 0) {

                $transport[] = $e->Title . '(' . $count . ')';
                $eventid[] = $e->id;
            }
            $count = 0;

        }

        return view('Profile.Profile',compact('First_name','Last_name','Email','user','transport','eventid'));

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

         $user = User::all();
         $resetuser = Resetuser::all();

         foreach($user as $u){

            if($u->email == $request['email'] && $u->email != $email->email)
                return redirect()->back()->with('oldemail',$request['email'])->withErrors(['email' => 'Tāds e-pasts jau eksistē']);

         }
         foreach($resetuser as $r){

            if($r->email == $request['email'] && $r->email != $email->email)
                return redirect()->back()->with('oldemail',$request['email'])->withErrors(['email' => 'Tāds e-pasts jau eksistē']);

         }

        if(Auth::user()->hasRole('Admin')){

            $events = Events::where('email',$email->email)->get();

            foreach($events as $e){

                $e->email = $request->get('email');
                $e->save();
            }

        }

        $reservations = Reservation::where('email',$email->email)->get();

        foreach($reservations as $r){

            $r->email = $request->get('email');
            $r->save();

        }

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

        if(Auth::user()->hasRole('Admin')){

            $events = Events::where('email',$user->email)->get();

            foreach($events as $e){

                $e->email = $resetuser->email;
                $e->save();
            }

        }

        $reservations = Reservation::where('email',$user->email)->get();

        foreach($reservations as $r){

            $r->email =$resetuser->email;
            $r->save();

        }


        $user->fill([
            'email' => $resetuser->email,
            'password' => $resetuser->password,
        ]);
        $user->save();

        return redirect()->back()->with('message','Jūsu e-pasts un parole tika veiksmīgi atjaunoti!');

    }
    public function sendemail(createProfileRequest $request){

        if($request['inlineDefaultRadiosExample'] == 'Yes'){

            $button[] = $request['buttontitle'];
            $button[] = $request['buttonlink'];

        }
        else $button = NULL;

        $text = str_replace("\r\n",'<br>',$request['emailtext']);

        if($request['action'] == 'preview'){

            $title = $request['emailtitle'];
            $preview = true;

            return view('Emails.Customemail',compact('title','text','button','preview'));

        }
        else{
        
            if($request['transportcb'] == 'on') {

                $transportemails = array();
                $reservations = Reservation::where('EventID',$request['transport'])->get();

                foreach($reservations as $r){

                    if($r->Transport != "Patstāvīgi") $transportemails[] = $r->email;

                }
                Mail::send(new CustomEmail($transportemails,$request['emailtitle'],$text,$button));

            }
            else Mail::send(new CustomEmail($request['reciever'],$request['emailtitle'],$text,$button));

            return redirect()->back()->with('emailmessage','E-pasts tika aizsūtīts');

        }
    }
}
