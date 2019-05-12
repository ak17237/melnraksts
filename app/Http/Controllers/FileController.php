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
    public function deletefile($id,$filename){

        $event = Events::find($id);
        $filename = $event->imgextension;

        Storage::disk('public')->delete($filename);

        $event->fill(['imgextension' => NULL]);
        $event->save();

        return redirect()->route('showedit',$id)->with('message','Pasākuma foto ir veiksmīgi dzēsts!');

    }
    public function downloadpdf($pdfname){

        return response()->download(public_path() . '/event-pdf' . '/' . $pdfname);

    }
    public function pdfdelete(Request $request,$id){

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
    public function showgallery($id){

        $gallery = Gallery::where('Event_ID',$id)->get();

        return view('Event_forms.gallery',compact('gallery','id'));

    }
    public function uploadgallery(createGalleryRequest $request,$id){

        if($request->hasFile('gallery')){

            for($i = 0;$i < sizeof($request['gallery']);$i++){

                    $name = $request['gallery.' . $i]->getClientOriginalName();
                    Storage::disk('gallery')->put($name,File::get($request['gallery.' . $i]));

                    Gallery::create([
                        'Event_ID' => $id,
                        'Name' => $name,
                    ]);
            }
            
        }

        return redirect()->route('showgallery',$id)->with('message','Pasākuma galerijas attēli ir veiksmīgi pievienoti!');

    }
    public function deletegallery(Request $request,$id){

        $counter = 0; // lai uzzināt pdf failu skaitu lapā

        while($request->has('imgname' . $counter) == true) $counter++; // uzzinam pdf skaitu

        for($i = 0;$i < $counter;$i++){ // ja checkbox ir atzīmēts,tad tieši šo nosaukumu izdzēšam no datubāzes un no servera

            if($request['imgcheckbox' . $i] == 'on'){

                Gallery::where('Name',$request['imgname' . $i])->delete();

                Storage::disk('gallery')->delete($request['imgname' . $i]);

            }

        }
        return redirect()->route('showgallery',$id)->with('message','Pasākuma galerijas attēli ir veiksmīgi dzēsti!');

    }
    public function downloadreport($id){

        $count = 0; // Lai uzzināt cik vajag kolonnas tabulai
        $data = array(); // dokumenta kolonnas dati

        $reservate = resrvcount($id); // funckija kas atdod masīvu ar datiem par rezervāiju 0 - Biļešu skaits,1 - Sēdvietu skaits,2 - Galdu sēdvietu skaits,4 - Galdu skaits

        $report = new TemplateProcessor('Report-Template.docx'); // izveidojam klasi no template

        $event = Events::find($id); // atrodam pasākumu

        $report->setValue('nosaukums',$event->Title); // aizpildam dokumenta lauku

        $user = User::where('email', $event->email)->first(); // atrodam kurš izveidoja pasākumu

        $report->setValue('autors',$user->First_name . ' ' . $user->Last_name); // ievietojam dokumentā
        $report->setValue('adrese',$event->Address);

        if($event->Tablenumber === 0) $galduteksts = 'nebija paredzēti galdi'; // ja galdu nebija
    
        else {
            
            $galduteksts = 'bija paredzēti ' . $event->Tablenumber . ' galdi';
            $count++; // pievienojam rindu,būs ko ielikt atskaites kolonā jo ir info par galdiem jo tie bija paredzēti un drizāk arī rezervēti
            $data[2] = array('Veids' => 'Galdu skaits','Paredzēti' => $event->Tablenumber,'Rezervēti' => $reservate[4]); // dati par pasākumu
        }

        if($event->Seatnumber === 0) $sedvietuteksts = 'nebija paredzētas sēdvietas'; // ja sēdvietu nebija
        else {
            
            $sedvietuteksts = 'bija paredzētas ' . $event->Seatnumber . ' sēdvietas';
            $count++;
            $data[1] = array('Veids' => 'Sēdvietu skaits','Paredzēti' => $event->Seatnumber,'Rezervēti' => $reservate[1]);
        }

        $report->setValue('galduteksts',$galduteksts);
        $report->setValue('sedvietuteksts',$sedvietuteksts);

        if($event->Tickets === -999) $bilesuskaits = 'neierobežots';
        else { 

            $bilesuskaits = $event->Tickets;
            $count++;
            $data[0] = array('Veids' => 'Biļešu skaits','Paredzēti' => $event->Tickets,'Rezervēti' => $reservate[0]);
        }

        $report->setValue('bilesuskaits',$bilesuskaits);

        $standcount = $event->Tickets - ($event->Seatnumber + ($event->Tablenumber * $event->Seatsontablenumber)); // reiķinam stāvvietu skaitu

        if($standcount > 0) {
            
            $count++; // ja bija stāvvietas tad pieliekam vēl kolonnu
            $data[3] = array('Veids' => 'Stāvvietu skaits','Paredzēti' => $standcount,'Rezervēti' => $reservate[0] - ($reservate[1] + $reservate[2]));
        }

        $report->cloneRow('veids',$count); // izveidojam tik kolonnas cik vajadzīgas

        for($i = 0;$i < $count;$i++){

            $j = $i + 1;

            $report->setValue('veids#' . $j,$data[$i]['Veids']);
            $report->setValue('paredzetas#' . $j,$data[$i]['Paredzēti']);
            $report->setValue('rezervetas#' . $j,$data[$i]['Rezervēti']);

        }
        $report->setValue('apraksts',$event->Description);

        if($event->VIP === 0) $report->setValue('vip','');
        else $report->setValue('vip','Šis bija vip pasākums');

        $path = 'event-report/' . str_replace(' ', '_', $event->Title) . '_report.docx';

        $report->saveAs($path);

        return response()->download($path)->deleteFileAfterSend();
    }
        
}
