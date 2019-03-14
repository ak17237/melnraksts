<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events;

class HomeController extends Controller
{
    public function index(){

        date_default_timezone_set("Europe/Riga"); // saņem rīgs laika joslu

        function check($i){ // pārbauda vai eksistē pasākumi ar $i pārbīdi no tekošā mēneša 
            return Events::where('Datefrom','like','%' . '-' . date('m',strtotime('+'. $i .'Months')) . '-' . '%')->exists();
        }
        function get($i){ // saņem visus pasākumus kuri ir noteiktajā mēnesī ar melnraksta statusu 0 un atlasa tos augošajā secībā
            return Events::where('Datefrom','like','%' . '-' . date('m',strtotime('+'. $i .'Months')) . '-' . '%')->where('Melnraksts',0)->get()->sortBy(['Datefrom']);
        }

        if(check(0)) $data = get(0); else $data = '';// aizpildīšana ar visiem datiem
        if(check(1)) $dataplus1 = get(1); else $dataplus1 = '';
        if(check(2)) $dataplus2 = get(2); else $dataplus2 = '';
        if(check(3)) $dataplus3 = get(3); else $dataplus3 = '';
        if(check(4)) $dataplus4 = get(4); else $dataplus4 = '';
        if(check(5)) $dataplus5 = get(5); else $dataplus5 = '';
        
        return view('home',compact('data','dataplus1','dataplus2','dataplus3','dataplus4','dataplus5'));

    }
}
