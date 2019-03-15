@extends('welcome')
@section('content')

<div class="container">
    <br>
        <a href="javascript:history.go(-1)" class="btn btn-primary back">Back</a>
        <div class="row">
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
                    @if(Auth::check())
                    <a href="{{ route('showreservationcreate',$myevent->id) }}" class="btn btn-primary btn-block">Rezervēt</a>
                    @endif
                </div>

            </div>
        </div>
</div>

@endsection