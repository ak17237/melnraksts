@extends('welcome')
@section('content')

<div class="container">
        <a href="javascript:history.go(-1)" class="btn btn-primary back">Atpakaļ</a>
    <br>
    @if($myevent->Melnraksts === 1) <i>Šīs pasākums ir redzams tikai administrātoriem,jo viņš vēl nav publicēts un ir melnrakstos</i> @endif
    <div class="row">
        <div class="col-lg-offset-3 col-lg-11 center">
            <div class="col-lg-12" style="height: 56px;">
                @if(Auth::check() && Auth::user()->hasRole('Admin'))
                <p style="float:left;font-size: 21px;">Rezervācijas links: </p>
                <input id="reservlink" class="form-control col-lg-5 eventcreate" value="{{ route('showreservationcreate', ['id' => $myevent->id,'extension' => $myevent->linkcode]) }}">
                <button id="copybtn" type="button" class="btn btn-secondary clippy eventcreate editcpy">
                    <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="25" height="25">
                </button>
                    @endif
            </div>
            @if(session()->has('message'))
                <div class="alert alert-dismissible alert-success" style="margin-top: 79px;margin-bottom: 20px;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p class="mb-0">{{ session()->get('message') }}</p>
                </div>
            @endif
            @if(session()->get('info') === 'VIP')
                <div class="alert alert-dismissible alert-primary">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Tika izveidots VIP pasākums!</strong><p class="mb-0">Linku uz izveidoto VIP pasākumu var atrast slaiderī pie pasākuma,rediģēšanas formā un pie pasākuma apskata</p>
                </div>
            @endif
            <br><br><br>
            <div>
                <h2 class="content">{{ $myevent->Title }}</h2>
                @if(geteventdate($myevent->Datefrom) == geteventdate($myevent->Dateto)) {{-- Datuma korektra izvade --}}
                    <p class="content">{{ geteventdate($myevent->Datefrom) }}</p>
                @else
                    <p class="content">{{ geteventdate($myevent->Datefrom) . '-' . geteventdate($myevent->Dateto) }}</p>
                @endif
                <h6 class="content"><i>{{ $myevent->Address }}</i></h6>
                @if(Storage::disk('public')->has($myevent->imgextension))
                    <img src="{{ asset('event-images/' . $myevent->imgextension) }}" width="1000" height="500" class="event-image img-responsive">
                @endif
                <p class="content">{!! $description !!}</p>

                @foreach ($pdf as $p)

                <div class="col-lg-1 pdfdownload clickdownload"> <a href="/event-pdf/{{ $p->Name }}"></a>
                    <img src="{{ asset('png-icon.jpg') }}" alt="png" width="40" height="40">
                    <p class="small" style="height: 32px;">{{ $p->Name }}</p>
                    <a href="{{ route('downloadpdf',['pdfname' => $p->Name]) }}" class="download btn btn-outline-primary">Lejuplādēt</a>
                </div>
                
                @endforeach
                @if(Auth::check() && $myevent->VIP != 1 && $myevent->Melnraksts === 0)
                    <a style="float:left;" href="{{ route('showreservationcreate',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}" class="btn btn-primary reserv btn-block">Rezervēt</a>
                @endif
            </div>
        </div>
    </div>
</div> <br>

@endsection