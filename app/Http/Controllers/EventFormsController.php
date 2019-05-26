<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\createEventRequest;

use App\Events;
use App\Pdf;
use App\Gallery;
use App\Reservation;
use App\User;
use Auth;
use Mail;
use App\Mail\EventChange;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class EventFormsController extends Controller
{
    public function showcreate(){ // formas izvade ar pareizu datuma formātu
        date_default_timezone_set("Europe/Riga");
        $date = date("Y-m-d"); // formas laukos šodienas datums html date atribūta formātā

        return view('Event_forms.Eventcreate',compact('date'));

    }
    public function showedit($id){ // pasākumu rediģēšanas formas izvade
    // vajag pievienot pārbaudi vai ir tāds pasākums līdzīgi kā rezervācijā
        $myevent = Events::find($id);
        $pdf = Pdf::where('Event_ID',$id)->get(); // saņemam visusapdfus pasākumam
        if($myevent->Tickets == -999) $checkedtickets = false; // pārbaude vai deaktivēt inputus un saglabāt radio izvēles
        else $checkedtickets = true;
        if($myevent->Seatnumber == 0) $checkedseats = false;
        else $checkedseats = true;
        if($myevent->Tablenumber == 0) $checkedtables = false;
        else $checkedtables = true;

        return view('Event_forms.Eventedit',['myevent' => $myevent,'checkedseats' => $checkedseats,'checkedtables' => $checkedtables,
        'checkedtickets' => $checkedtickets,'pdf' => $pdf]);
    }
    public function showsavedevents($page){ // parāda saglabātos pasākumus sākumā nesen izmainītos,kuriem ir melnraksts 1

        $counter = 1;

        $user = User::where('email', Auth::user()->email)->first();
// izveidojam pagination katrā lapā pa 5 elementiem
        $data = Events::where('Melnraksts',1)->where('user_id',$user->id)->orderBy('updated_at','DESC')->SimplePaginate(5,['*'], 'page', $page);
        $count = Events::where('Melnraksts',1)->where('user_id',$user->id)->count(); // saskaitam visus elementus
        $number = 1; // lai saskaitītu lapu skaitu
        while($count > 5){ // precīza paginēšanas url izvade un pogas tai
            $number++;
            $count = $count - 5;
        }
        for($i = 1;$i <= $number; $i++) $pagenumber[] = $i; // izvadīt pareizus linkus paginācijai
        return view('Event_forms.Savedevents',compact('data','pagenumber','counter'));
    }
    public function showevent($id){ // pasākuma apskates skats

        $myevent = Events::find($id);
        $pdf = Pdf::where('Event_ID',$id)->get(); // saņemam visus padf pasākumam

        $description = str_replace("\r\n",'<br>',$myevent->Description); // saņemto no datu bāzes lauka atstarpes pārveidojam html formātā

        return view('Event_forms.Eventinfo',compact('myevent','description','pdf'));
    }
    public function create(createEventRequest $request){ // pasākumu izveide un saglabāšana datu bāzē kļūdas pārbauda izveidotais request 
        
        if($request['action'] == 'save') $melnraksts = 1; // pārbaude vai saglabāt kā melnrakstu vai publicēt
        else $melnraksts = 0;

        eventvalidate($request); // funkcija no helpers.php,kura aizpilda tukšus un deaktivētus laukus ar vērtībām kuras ir korektas daubāzē
// ja biļetes neierobežotas datubāzē -999,ja nav galdu sēdvietu - 0
        $message = array( // ziņas izvade atkarībā no melnraksta statusa
            1 => 'saglabāts!',
            0 => 'izveidots!'
        );

        if(empty($request['vipswitch'])) $vip = 0; // pārbaudam vai vip pasākums tika atzīmēts
        else $vip = 1;

        if(empty($request['editableswitch'])) $editable = 0; // vai rediģējamas rezervācijas tika atzīmētas
        else $editable = 1;

        if($request['file'] == NULL) $img = NULL; // pārbauda vai ir fails,ja nav tad img mainīgs ir NULL  
        else $img = $request['file']->getClientOriginalName(); // ja fails ir saņem to pilnu nosaukumu un ieivieto mainīgajā img

        $user = User::where('email', Auth::user()->email)->first();     
        Events::create([  // ieraksta datus datubāzē 
        'Title' => $request['title'],
        'Datefrom' => $request['datefrom'],
        'Dateto' => $request['dateto'],
        'Address' => $request['address'],
        'Seatnumber' => $request['seatnr'],
        'Tablenumber' => $request['tablenr'],
        'Seatsontablenumber' => $request['seatsontablenr'],
        'Anotation' => $request['anotation'],
        'Description' => $request['description'],
        'Tickets' => $request['ticketcount'],
        'Melnraksts' => $melnraksts, // melnraksta status ir atkarīgs no kura poga tika uzpiesta
        'VIP' => $vip,
        'Editable' => $editable,
        'imgextension' => $img,
        'user_id' => $user->id, // lietotājs kurš izveidoja pasākumu
        ]);

        $event = Events::all()->sortByDesc(['updated_at'])->first(); // saņemam tikko ievietoto pasākumu

        if($vip == 1 && $request['action'] == 'create'){ // ja pasākums bija izveidots,kā VIP

            $info = 'VIP';
            $linkcode = generateRandomString(); // ģenerējas skaitļu un vārdu nejaušā kārtībā virkne
                $idarray = str_split($event->id); // sadalam id masīvā,lai izslēgt gadijumu,kad nejauši ģenerēta virkne var ar mazu varbūtību atkārtoties
    
            for($i = 0;$i < strlen($event->id);$i++){ // ievietojam virknē id vietās pēc katra burta
                    //id  pirmais numurs,virknes simbols,id otrais numurs,vēl viens virknes simbols utt.
    
                $linkcode = substr_replace($linkcode,$idarray[$i],$i*2,0); // saņemam kodu kutš būs mūsu piekļuves linkā
    
                }
    
            }else {
                $info = 0;
                $linkcode = "show"; // ja nav VIP,tad piekļuve kā visām parastām lapā ar show
            }
            $event->fill(['linkcode' => $linkcode]); // ievietojam ģenerēto kodu,kas ir kods linkā pasākumam
            $event->save(); // saglabājam pasākumam

        if($request->hasFile('pdffile')){ // ja tika ielādēts kaut viens pdf fails  ierakstam to nosaukumus un ielādējam serverī

            for($i = 0;$i < sizeof($request['pdffile']);$i++){

                    $name = $request['pdffile.' . $i]->getClientOriginalName(); // saņemam pilno vāŗdu
                    Storage::disk('pdf')->put($name,File::get($request['pdffile.' . $i])); // ievietojam izmantojot pdf ceļu,to var redzēt config/filesystems.php

                    Pdf::create([ // ierakstam datu bāzē
                        'Event_ID' => $event->id,
                        'Name' => $name,
                    ]);
            }
            
        }

        $file = $request['file']; // ja bija pievienots attēls ielādējam to,
        if($file){ // tākā tā nosaukums jau bija ierakstīts pasākuma tabulā tad tikai ielādējam serverī
            Storage::disk('public')->put($request['file']->getClientOriginalName(),File::get($file));
        }

        return redirect()->route('showevent',$event->id)->with('message','Pasākums ir veiksmīgi ' . $message[$melnraksts])->with('info',$info);
        
    }
    public function edit(createEventRequest $request,$id){ // līdzīgi kā create funkcijā

        $myevent = Events::find($id);
        
        $message = array( // ziņas izvade
            2 => 'saglabāts!', // ja saglabāts
            1 => 'publicēts!', // ja publicēts(atnāca no melnrakstiem)
            0 => 'izmainīts!' // ja publicēts(atnāca no slidera un reiģēja)
        );
        // $status - ja ir 2,tad pasākums eksistēja kā publicēts un to rediģēja pārvietojot melnrakstos
        // ja status ir 1(ņemot no datubāzes melnraksta statusu),tad tas bija melnrakstos un tika rediģēts un publicēts
        // ja status ir 0,tad tas bija publicēts un to vienkārsī izmainīja,joprojām publicēts

        if($request['action'] == 'save') { // ja saglabāts,lai labāk saprast skatīt redirect()
            $status = 2; // izvadīt ziņu par saglabāšanu ($status ir ziņas numurs $message)
            $index = 1; // jebkurā gadijumā rādīt melnrakstu sarakstu ($index ir url numurs $route)
        }
        else {
            $status = $myevent->Melnraksts; // ja tika rediģēts bez kļūdām,tad saglabāt ziņu atkarībā kāds status ir pirms izmainīšanas
            $index = 0; // ja publicēts tad melnraksts ir 0
        }

        eventvalidate($request); // funkcija no helpers,kura aizpilda deaktivētus laukus ar vajadzīgām vērtībām

        if($request['vipswitch'] == "off") $vip = 0; // VIP pasākuma pārbaude
        else $vip = 1;

        if($request['editableswitch'] == "off") $editable = 0; // rediģējamo pasākumu checkbox pārbaude
        else $editable = 1;

        if($vip == $myevent->VIP && $myevent->Melnraksts === 0){ // ja pasākuma VIP jau bija tādā pašā statusā kā tas tikko bija rediģēts un melnraksts bija 0
            
            $linkcode = $myevent->linkcode; // saglabāt to pašu linka kodu
            $info = 0;
        }
        elseif($vip == 1 && $request['action'] == 'create'){ // ja VIP ir rediģēts kā atzīmēts

            $info = 'VIP';
            $linkcode = generateRandomString(); // ģenerējas skaitļu un vārdu nejaušā kārtībā virkne
                $idarray = str_split($id); // sadalam id masīvā,lai izslēgt gadijumu,kad nejauši ģenerēta virkne var ar mazu varbūtību atkārtoties
    
            for($i = 0;$i < strlen($id);$i++){ // ievietojam virknē id vietās pēc katra burta
                    //id  pirmais numurs,virknes simbols,id otrais numurs,vēl viens virknes simbols utt.
    
                $linkcode = substr_replace($linkcode,$idarray[$i],$i*2,0);
    
                }
    
            }else { // pēdējais kas palika,ja vip nebija atzīmēts un tas nav vienāds ar datubāzi
                $info = 0;
                $linkcode = "show";
            }

        if($request['file'] == NULL) { // ja jauna faila nav

            if($myevent->imgextension == NULL) $img = NULL; // pārbauda vai šim pasākumam nav jau ielādēts foto
            else $img = $myevent->imgextension; // ja ir tad ievieto mainīgajā to pašu,ja nav un nebija tad ievieto NULL
            
        }
        else $img = $request['file']->getClientOriginalName(); // ja fails ir saņem to pilnu nosaukumu un ieivieto mainīgajā img

        $oldimg = $myevent->imgextension; // vecais attēla nosaukums

        if($myevent->Datefrom != $request['datefrom']) $eventchange[0] = true;  // pārbauda vai tika izmanīts datums jeb adrese 
        else $eventchange[0] = false; 
        if($myevent->Address != $request['address']) $eventchange[1] = true; // lai pēc tam izveidot korektu e-pasta ziņu
        else $eventchange[1] = false;

        $myevent->fill([    // ieraksta izmainīšana. Visu pārbaudīto un saņemto mainīgo ievietošana/izmainīšana datu bāzē
            'Title' => $request['title'],
            'Datefrom' => $request['datefrom'],
            'Dateto' => $request['dateto'],
            'Address' => $request['address'],
            'Seatnumber' => $request['seatnr'],
            'Tablenumber' => $request['tablenr'],
            'Seatsontablenumber' => $request['seatsontablenr'],
            'Anotation' => $request['anotation'],
            'Description' => $request['description'],
            'Tickets' => $request['ticketcount'],
            'Melnraksts' => $index,
            'VIP' => $vip,
            'Editable' => $editable,
            'imgextension' => $img,
            'linkcode' => $linkcode,
            ]);
        $myevent->save();

        if($request->hasFile('pdffile')){ // ja bija kādi jauni ielādēti pdfi

            for($i = 0;$i < sizeof($request['pdffile']);$i++){ // katru nosaukumu saņemam ierakstam datubāzē un serverī ielādējam failu

                    $name = $request['pdffile.' . $i]->getClientOriginalName();
                    Storage::disk('pdf')->put($name,File::get($request['pdffile.' . $i]));

                    Pdf::create([
                        'Event_ID' => $id,
                        'Name' => $name,
                    ]);
            }
            
        }
        
        $file = $request['file'];

        if($file){ // ja bija attēls ielādējam serverī un veco izdzēšam,ja bija null nekas neidzēsīsies
            Storage::disk('public')->put($request['file']->getClientOriginalName(),File::get($file));
            Storage::disk('public')->delete($oldimg);
        }
        
        $reservedusers = Reservation::where('EventID',$id)->get(); // saņemam rezervētos lietotājus

        if($eventchange[0] || $eventchange[1]) { // ja pasākumam bija izmainīta adrese jeb datums

            foreach($reservedusers as $reserveduser){

                $user[] = User::find($reserveduser->user_id); // saņemam lietotāju no katras rezervāijas
    
            }
            Mail::send(new EventChange($reserveduser,$user,$myevent,$eventchange)); // tālākās pārbaudes,kam sūtīt būš EventChange klasē
            
        }

        if($myevent->wasChanged()) // ja pasākums nebija izmainīts pasakam to.
            return redirect()->route('showevent',$id)->with('message','Pasākums ir veiksmīgi ' . $message[$status])->with('info',$info);
        else return redirect()->route('showevent',$id)->with('message','Pasākumā nebija veiktas izmaiņas')->with('info',$info);

}
    public function delete($id){ // pasākuma dzēšana

        $myevent = Events::find($id); // saņemam pasākumu
        $reservations = Reservation::where('EventID',$id)->get(); // rezervācijas pasākumam
        $galleries = Gallery::where('Event_ID',$id)->get(); // visus galerijas attēlus pasākumam
        $pdfs =  Pdf::where('Event_ID',$id)->get(); // pdfus pasākumsm

        foreach($reservations as $r){ // dzēšam visas rezervācijas pasākumsm

            
            $r->delete();

        }
        foreach($galleries as $g){ // visus attēlus galerijā pasākumam,no datubāzes un no servera mapes

            Storage::disk('gallery')->delete($g->Name);
            $g->delete();

        }
        foreach($pdfs as $p){ // visus pdfus pasākumam,no datubāzes un no servera mapes

            Storage::disk('pdf')->delete($p->Name);
            $p->delete();

        }


        $filename = $myevent->imgextension; // dzēšam attēlu pasākumam

        Storage::disk('public')->delete($filename);


        if($myevent->Melnraksts == 1){ // ja dzēsts melnraksts atgriezt uz melnrakstiem

            Events::find($id)->delete();
            return redirect('/saved-events-1')->with('message','Pasākums ir dzēsts.');
    
            }
            else{ // ja nē atgriez galvenā lapā
    
                Events::find($id)->delete();
                return redirect()->route('home')->with('message','Pasākums ir dzēsts.');
    
            }

    }
    public function showqrcode($id){ // rāda pasākuma biļešu skanēšanas lapu

        return view('Event_forms.qrcode',compact('id'));

    }
    public function qrcode($id,Request $request){ // qr koda pārbaude

        $reserv = Reservation::where('EventID',$id)->where('QRcode', $request['qrcode'])->first(); // rezervācija pasākumam ar noskanēto qr kodu

        if($reserv == null)  // ja tādu nav kļūda
            return redirect()->back()->withErrors(['qrcode' => 'Šim pasākumam šis kods ir nederīgs!']);
        else{ // ja tāda ir tad viss ir veiksmīgi

            if($reserv->Attendance == false){ // ja biļete jau bija noskanēto neko neierakstam

                $reserv->fill(['Attendance' => true]);
                $reserv->save();
                
            }
            return redirect()->back()->with('message','QR kods tika veiksmīgi pārbaudīts!');

        }

    }

}
