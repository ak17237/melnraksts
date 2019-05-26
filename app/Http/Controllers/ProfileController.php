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

        $First_name = User::getFirstname(); // saņem vārdu
        $Last_name = User::getLastname(); // uzvārdu
        $Email = User::getEmail(); // e-pastu
        $user = User::all(); // saņem visus lietotājus
        $events = Events::whereDate('Datefrom', '>', date("Y-m-d"))->get(); // pasākumus kuri vēl nav pagājuši
        $count = 0; // rezervāciju autobusu braucēju skaitīšanai
        $transport = array(); // rezervācijas pasākuma nosaukumi ar bracuēju sakitu
        $eventid = array(); // pasākumi ID

        foreach($events as $e){ // katram pasākumam pārbaudam braucējus un to sakitu

            $reservation = Reservation::where('EventID',$e->id)->get(); // saņemam rezervācijas šim pasākumam
            
            foreach ($reservation as $r){ // katrai rezervācijai kur transports nav patstāvīgi palielinam sakitu par 1 count mainīgajā

                if($r->Transport != "Patstāvīgi") $count++; 

            }
            if($count > 0) { // ja bija vismaz viens braucējs

                $transport[] = $e->Title . '(' . $count . ')'; // ievietojam masīvā kurš būs select atribūtā options nosaukums
                $eventid[] = $e->id; // ievietojam masīvā pasākuma id kas būs options value
            }
            $count = 0; // atgrizam atakl uz 0 un skaitam nākošo pasākumu

        }
        return view('Profile.Profile',compact('First_name','Last_name','Email','user','transport','eventid'));

    }
    public function changeavatar(createProfileRequest $request){ // attēla izmaiņa,ja funkcija izpildās tad attēls ir

        $user = User::where('email', Auth::user()->email)->first(); // saņemam autentificēto lietotāju

        if(Storage::disk('avatar')->has(Auth::user()->Avatar))  // ja mapē ir jau kāda bilde kura ir piesaistīta lietotājam
            Storage::disk('avatar')->delete(Auth::user()->Avatar); // dzēšam to

        $file = $request['avatar']; 
        if($file){ 
            Storage::disk('avatar')->put($request['avatar']->getClientOriginalName(),File::get($file)); // ielādējam jaunu
        }

        $user->fill([ // aizpildam datus ar jauno nosaukumu
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
    public function changesurname(createProfileRequest $request){ // paņem ielogotā lietotāja datus un maina tos ar ievadītiem

         $Last_name = User::where('email', Auth::user()->email)->first();
         $Last_name->Last_name = $request->get('lname');
         $Last_name->save();
         return redirect()->back()->with('message','Jūsu uzvārds tika veiksmīgi izmainīts');

    }
    public function changeemail(createProfileRequest $request){ // paņem ielogotā lietotāja datus un maina tos ar ievadītiem

         $email = User::where('email', Auth::user()->email)->first();

         $user = User::all();
         $resetuser = Resetuser::all();

         foreach($user as $u){ // pārbaude uz to vai kādam jau eksistē šāds e-pasts

            if($u->email == $request['email'] && $u->email != $email->email)
                return redirect()->back()->with('oldemail',$request['email'])->withErrors(['email' => 'Tāds e-pasts jau eksistē']);

         }
         foreach($resetuser as $r){ // pārbauda arī vēsturiskos datus

            if($r->email == $request['email'] && $r->email != $email->email)
                return redirect()->back()->with('oldemail',$request['email'])->withErrors(['email' => 'Tāds e-pasts jau eksistē']);

         }

         $email->email = $request->get('email'); // ja kļūdu nav ierakstam
         $email->save();

         if($request->cookie('email') != null) $cookie_email = cookie('email', $request['email'], 60 * 24 * 30); // mainam coockie uz izmainītiem
         else $cookie_email = cookie('email','',-1); // ja coockie nav,tad lietotājs ir ielogots bez remember me un mainīt neko nevajag

         return redirect()->back()->cookie($cookie_email)->with('message','Jūsu e-pasts tika veiksmīgi izmainīts');

    }
    public function changepassword(createProfileRequest $request){ // paroles pārbaudīšana un jaunā ierakstīšana

        $password = User::where('email', Auth::user()->email)->first()->password;
        if (Hash::check($request['oldpassword'],$password)){ // ja parole sakrīt,var sākt paroles mainīšanas processu

            $user = User::where('email', Auth::user()->email)->first();
            $user->password = Hash::make($request['password']); // ieraksta jaunu paroli ielogotam lietotājam
            $user->save();

            if($request->cookie('password') != null) $cookie_password = cookie('password', $request['password'], 60 * 24 * 30);
            else $cookie_password = cookie('password','',-1); // atjaunojam passowrd coockie ja remember me ir,ja remember me ir tad coockie ir,ja nav tad arī coockie nebūs

            return redirect()->route('profile.index')->cookie($cookie_password)->with('message','Jūsu parole tika veiksmīgi izmainīta');
        } // ja parole nesakrīt izvadam kļūdu
        else return redirect()->back()->withInput($request->input())->withErrors(['oldpassword' => 'Nepareiza parole']);

    }
    public function Reset(Request $request){ // autentifikācijas datu atjaunošana

        $user = User::where('email',Auth::user()->email)->first(); // ielogots lietotājs
        $resetuser = Resetuser::where('id',$user->id)->first(); // ielogotā lietotāja vēsturiskie dati

        $user->fill([ // mainam lietotāja datus uz vēsturiskiem
            'email' => $resetuser->email,
            'password' => $resetuser->password,
        ]);
        $user->save();

        if($request->cookie('email') != null) $cookie_email = cookie('email', $request['email'], 60 * 24 * 30);
        else $cookie_email = cookie('email','',-1); // mainam coockie tāpat kā changepassword funkcijā un changeemail
        if($request->cookie('password') != null) $cookie_password = cookie('password', $request['password'], 60 * 24 * 30);
        else $cookie_password = cookie('password','',-1);

        return redirect()->back()->cookie($cookie_password)->cookie($cookie_email)->with('message','Jūsu e-pasts un parole tika veiksmīgi atjaunoti!');

    }
    public function sendemail(createProfileRequest $request){ // e-pasta sūtīšana

        if($request['inlineDefaultRadiosExample'] == 'Yes'){ // ja tika atzīmēts pogas links

            $button[] = $request['buttontitle']; // saņemam pogas virsrakstu un linku
            $button[] = $request['buttonlink'];

        }
        else $button = NULL; // ja nav pogas nebūs

        $text = str_replace("\r\n",'<br>',$request['emailtext']); // teksta atstarpes aizvietojam uz html saprotamajām

        if($request['action'] == 'preview'){ // ja lietotājs izvēlējās apskatīties pirms sūtīšanas

            $title = $request['emailtitle']; // atgriežam skatu ar e-pastu
            $preview = true;

            return view('Emails.Customemail',compact('title','text','button','preview'));

        }
        else{ // ja lietotājs sūta e-pastu
        
            if($request['transportcb'] == 'on') { // pārbaudam vai sūta transpora braucējiem

                $transportemails = array(); // braucēju e-pasti
                $reservations = Reservation::where('EventID',$request['transport'])->get(); // rezervācijas noteitam pasākumam

                foreach($reservations as $r){ // visi lietotāju e-pasti kuri nebrauc patstāvīgi

                    if($r->Transport != "Patstāvīgi") $transportemails[] = User::where('id',$r->user_id)->first()->email;

                } // sūtam klasē Custom email,padodot,saņēmēju e-pastus,e-pasta virsrakstu,e-pasta tekstu un info par pogu e-pastā
                Mail::send(new CustomEmail($transportemails,$request['emailtitle'],$text,$button));

            }// sūtam klasē Custom email,padodot,saņēmēju e-pastus,e-pasta virsrakstu,e-pasta tekstu un info par pogu e-pastā
            else Mail::send(new CustomEmail($request['reciever'],$request['emailtitle'],$text,$button));

            return redirect()->back()->with('emailmessage','E-pasts tika aizsūtīts');

        }
    }
}
