@extends('welcome')
@section('content')

<div class="container">
    <br>
    <div class="col-lg-11" style="height: 50px;">
        <a href="javascript:history.go(-1)" class="btn btn-primary back eventcreate">Back</a>
        @if(Auth::check() && Auth::user()->hasRole('Admin'))
        <input id="reservlink" class="form-control col-lg-5 eventcreate" value="{{ route('showreservationcreate', ['id' => $myevent->id,'extension' => $myevent->linkcode]) }}">
        <button id="copybtn" type="button" class="btn btn-secondary clippy eventcreate">
            <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="25" height="25">
        </button>
        @endif
    </div>
        <div class="row block eventcreate" style="width: 100%;">
            <div class="col-lg-offset-3 col-lg-11">
                <div>
                    <h2>{{ $myevent->Title }}</h2>
                    @if(geteventdate($myevent->Datefrom) == geteventdate($myevent->Dateto)) {{-- Datuma korektra izvade --}}
                    <p>{{ geteventdate($myevent->Datefrom) }}</p>
                    @else
                    <p>{{ geteventdate($myevent->Datefrom) . '-' . geteventdate($myevent->Dateto) }}</p>
                    @endif
                    <h6><i>{{ $myevent->Address }}</i></h6>
                    <p>{!! $description !!}</p>
                    @if(Auth::check() && $myevent->VIP != 1)
                    <a href="{{ route('showreservationcreate',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}" class="btn btn-primary btn-block">Rezervēt</a>
                    @endif
                </div>

            </div>
        </div>
</div>

@endsection