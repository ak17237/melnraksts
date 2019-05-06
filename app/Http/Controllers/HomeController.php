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

        $pages = 5;

        for($i = 0;$i <= $pages;$i++){
            if(check($i)) $data[$i] = get($i); else $data[$i] = '';
        }
        
        $count = 0;
        
        return view('home',compact('data','pages','count'));

    }
}
