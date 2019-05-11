<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\createGalleryRequest;
use App\Events;
use App\Pdf;
use App\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
}
