@extends('welcome')
@section('content')

<div class="container">
        <a href="javascript:history.go(-1)" class="btn btn-primary back">Atpakaļ</a>
    <br>
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
            <br><br><br>
            <div>
                <h2 class="content">{{ $myevent->Title }}</h2>
                @if(geteventdate($myevent->Datefrom) == geteventdate($myevent->Dateto)) {{-- Datuma korektra izvade --}}
                    <p class="content">{{ geteventdate($myevent->Datefrom) }}</p>
                @else
                    <p class="content">{{ geteventdate($myevent->Datefrom) . '-' . geteventdate($myevent->Dateto) }}</p>
                @endif
                <h6 class="content"><i>{{ $myevent->Address }}</i></h6>
                @if(Storage::disk('public')->has(str_replace(' ', '_',$myevent->Title) . '-' . $myevent->id . '.' . $myevent->imgextension))
                    <img src="{{ asset('event-images/' . str_replace(' ', '_',$myevent->Title) . '-' . $myevent->id . '.' . $myevent->imgextension) }}" width="1000" height="500" class="img-responsive">
                @endif
                <p class="content">{!! $description !!}</p>
                @if(Auth::check() && $myevent->VIP != 1)
                    <a href="{{ route('showreservationcreate',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}" class="btn btn-primary reserv btn-block">Rezervēt</a>
                @endif
            </div>
        </div>
    </div>
</div> <br>

@endsection