<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\createGalleryRequest;
use App\Events;
use App\Pdf;
use App\Gallery;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;

class FileController extends Controller
{
    public function deletefile($id,$filename){ // attēla dzēšana pasākumam

        $event = Events::find($id);
        $filename = $event->imgextension; // saņēmam to nosaukumu

        Storage::disk('public')->delete($filename); // dzēšam pēc nosaukuma

        $event->fill(['imgextension' => NULL]); // mainam datubāzes vērtību attēlam
        $event->save();

        return redirect()->route('showedit',$id)->with('message','Pasākuma foto ir veiksmīgi dzēsts!');

    }
    public function downloadpdf($pdfname){ // pdf lejuplāde

        return response()->download(public_path() . '/event-pdf' . '/' . $pdfname); // pdf glabāšanās mape

    }
    public function pdfdelete(Request $request,$id){ // pdf dzēšana

        $counter = 0; // lai uzzināt pdf failu skaitu lapā

        while($request->has('pdfname' . $counter) == true) $counter++; // uzzinam pdf skaitu

        for($i = 0;$i < $counter;$i++){ // ja checkbox ir atzīmēts,tad tieši šo nosaukumu izdzēšam no datubāzes un no servera

            if($request['pdfcheckbox' . $i] == 'on'){

                Pdf::where('Name',$request['pdfname' . $i])->delete();

                Storage::disk('pdf')->delete($request['pdfname' . $i]);

            }

        }

        return redirect()->route('showedit',$id)->with('message','Pasākuma pielikumi ir veiksmīgi dzēsti!');

    }
    public function showgallery($id){ // parādam visus attēlus šim pasākumam

        $gallery = Gallery::where('Event_ID',$id)->get();

        return view('Event_forms.gallery',compact('gallery','id'));

    }
    public function uploadgallery(createGalleryRequest $request,$id){ // attēlu ielāe

        if($request->hasFile('gallery')){ // ja ir ko lādēt

            for($i = 0;$i < sizeof($request['gallery']);$i++){ //katram attēlam

                    $name = $request['gallery.' . $i]->getClientOriginalName(); // saņemam to nosaukumu pilno
                    Storage::disk('gallery')->put($name,File::get($request['gallery.' . $i])); // ielādējam serverī

                    Gallery::create([ // to nosaukumu ierakstam datubāzē
                        'Event_ID' => $id,
                        'Name' => $name,
                    ]);
            }
            
        }

        return redirect()->route('showgallery',$id)->with('message','Pasākuma galerijas attēli ir veiksmīgi pievienoti!');

    }
    public function editgallery($id,Request $request){ // pasākuma apraksta rediģēšana

       $description = $request->except('_token'); // saņemam visus laukus
       $keys = array_keys($description); // un to identificēšanas laukus

       for($i = 0;$i < sizeof($request->except('_token'));$i++){ // katram rediģētam laukam

        $photoid = explode('-',$keys[$i]); // saņemam attēla id kuram ir jāielādē attēls,nosaukumā pēc noteiktas struktūras inputam ir id vārda nosaukumā
// ja tas tika padots tad tika atzīmēts un drīzāk arī mainīts
        $gallery = Gallery::find($photoid[1]); // atrodam vajadzīgo attēlu
        $gallery->fill(['Description' => $description[$keys[$i]]]); // un ielādējam vajadzīgo lauku zem tā nosaukuma ar kuru tas tika padots
        $gallery->save();
       }

        return redirect()->route('showgallery',$id)->with('message','Attēlu apraksti ir veiksmīgi rediģēti!');

    }
    public function deletegallery(Request $request,$id){ // attēlu dzēšana

        $counter = 0; // lai uzzināt attēlu failu skaitu lapā

        while($request->has('imgname' . $counter) == true) $counter++; // uzzinam attēlu skaitu

        for($i = 0;$i < $counter;$i++){ // ja checkbox ir atzīmēts,tad tieši šo nosaukumu izdzēšam no datubāzes un no servera

            if($request['imgcheckbox' . $i] == 'on'){

                Gallery::where('Name',$request['imgname' . $i])->delete();

                Storage::disk('gallery')->delete($request['imgname' . $i]);

            }

        }
        return redirect()->route('showgallery',$id)->with('message','Pasākuma galerijas attēli ir veiksmīgi dzēsti!');

    }
    public function downloadreport($id){ // atskaites lejuplādēšana

        $count = 0; // Lai uzzināt cik vajag kolonnas tabulai
        $data = array(); // dokumenta kolonnas dati

        $reservate = resrvcount($id); // funckija kas atdod masīvu ar datiem par rezervāiju 0 - Biļešu skaits,1 - Sēdvietu skaits,2 - Galdu sēdvietu skaits,4 - Galdu skaits
        $attendance = attendance($id); // saņem pasākuma apmeklētāju skaitu (0 - biļešu skaits,1 - sēdvietu skaits,2 - galdu skaits,3 - stāvvietu skaits)

        $report = new TemplateProcessor('Report-Template.docx'); // izveidojam klasi no template,lai ierakstīt datus sagatavotā dokumentā

        $event = Events::find($id); // atrodam pasākumu

        $report->setValue('nosaukums',$event->Title); // aizpildam dokumenta lauku,pasākuma nosaukums

        $user = User::find($event->user_id); // atrodam kurš izveidoja pasākumu

        $report->setValue('autors',$user->First_name . ' ' . $user->Last_name); // ievietojam dokumentā
        $report->setValue('adrese',$event->Address); // pasākuma adrese

        if($event->Tickets === -999) $bilesuskaits = 'neierobežots'; // ja biļetes bija neirobežotas,tad kolonnas biļešu skaitam neveidojam
        else { // ja bija

            $bilesuskaits = $event->Tickets; // ievietojam kolonnas datus masīvā
            $data[$count] = array('Veids' => 'Biļešu skaits','Paredzēti' => $event->Tickets,'Rezervēti' => $reservate[0],'Apmeklējums' => $attendance[0]);
            $count++;
            
        }

        $report->setValue('bilesuskaits',$bilesuskaits);

        if($event->Seatnumber === 0) $sedvietuteksts = 'nebija paredzētas sēdvietas'; // ja sēdvietu nebija,ievietojam ziņojumā ziņa
        else { // tas pats kā 139-146 rindiņās
            
            $sedvietuteksts = 'bija paredzētas ' . $event->Seatnumber . ' sēdvietas';
            $data[$count] = array('Veids' => 'Sēdvietu skaits','Paredzēti' => $event->Seatnumber,'Rezervēti' => $reservate[1],'Apmeklējums' => $attendance[1]);
            $count++;
            
        }

        if($event->Tablenumber === 0) $galduteksts = 'nebija paredzēti galdi'; // ja galdu nebija
    
        else { // tas pats kā 139-146 rindiņās
            
            $galduteksts = 'bija paredzēti ' . $event->Tablenumber . ' galdi';
            $data[$count] = array('Veids' => 'Galdu skaits','Paredzēti' => $event->Tablenumber,'Rezervēti' => $reservate[4],'Apmeklējums' => $attendance[2]); // dati par pasākumu
            $count++; // pievienojam rindu,būs ko ielikt atskaites kolonā jo ir info par galdiem jo tie bija paredzēti un drizāk arī rezervēti
           
        }

        $report->setValue('galduteksts',$galduteksts);
        $report->setValue('sedvietuteksts',$sedvietuteksts);

        $standcount = $event->Tickets - ($event->Seatnumber + ($event->Tablenumber * $event->Seatsontablenumber)); // reiķinam stāvvietu skaitu

        if($standcount > 0) { // ja bija paredzētas stāvvietas
            // tas pats kā 139-146 rindiņās
            $data[$count] = array('Veids' => 'Stāvvietu skaits','Paredzēti' => $standcount,'Rezervēti' => $reservate[0] - ($reservate[1] + $reservate[2]),'Apmeklējums' => $attendance[3]);
            $count++; // ja bija stāvvietas tad pieliekam vēl kolonnu
            
        }

        $report->cloneRow('veids',$count); // izveidojam tik kolonnas cik bija saskaitītas

        for($i = 0;$i < $count;$i++){ // aizpildam kolonnas

            $j = $i + 1;

            $report->setValue('veids#' . $j,$data[$i]['Veids']); // katrā masīva elementā ir vēl 4 elementi ar datiem kurus mēs saņemam
            $report->setValue('paredzetas#' . $j,$data[$i]['Paredzēti']);
            $report->setValue('rezervetas#' . $j,$data[$i]['Rezervēti']);
            $report->setValue('atnaca#' . $j,$data[$i]['Apmeklējums']);

        }
        $report->setValue('apraksts',$event->Description); // apraksts
        $report->setValue('atnaca',$attendance[0]); // apmeklētāju skaits
        $report->setValue('rezervetas',$reservate[0]); // rezervāciju skaits

        if($event->VIP === 0) $report->setValue('vip',''); // ja tas bija vip pasākums pievienojam ziņu par to
        else $report->setValue('vip','Šis bija vip pasākums');

        $path = 'event-report/' . str_replace(' ', '_', $event->Title) . '_report.docx'; // dokumenta ceļs

        $report->saveAs($path); // saglabājam pēc noteikta ceļa

        return response()->download($path)->deleteFileAfterSend(); // dodam lietotājam lejuplādēt un pēc tam idzēšam to
    }
        
}
