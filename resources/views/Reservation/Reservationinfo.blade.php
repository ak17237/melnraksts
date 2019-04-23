@extends('welcome')
@section('content')

<div class="container">
    <br>
        <a href="javascript:history.go(-1)" class="btn btn-primary back">Back</a>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-11">

                    @if(session()->has('message'))
                    <div class="alert alert-dismissible alert-success">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <p class="mb-0">{{ session()->get('message') }}</p>
                    </div>
                    @endif

                        <legend>Manas rezervācijas</legend>
                            <div class="col-lg-5 eventcreate">
                                <label>Title</label>
                                <h4>{{ $myevent->Title }}</h4>
                            </div>                   
                    
                            <div class="col-lg-3 eventcreate">
                                <label>
                                    @if(geteventdate($myevent->Datefrom) != geteventdate($myevent->Dateto))
                                    Date from
                                    @else Date
                                    @endif
                                </label>
                                <h5>{{ geteventdate($myevent->Datefrom) }}</h5>
                            </div>
                            @if(geteventdate($myevent->Datefrom) != geteventdate($myevent->Dateto)) {{-- Datuma korektra izvade --}}
                            <div class="col-lg-3 eventcreate">
                                <label>Date to</label>
                                <h5>{{ geteventdate($myevent->Dateto) }}<h5>
                            </div>
                            @endif


                            <div class="col-lg-11 eventcreate">
                                <label>Address</label>
                                <h6>{{ $myevent->Address }}</h6>
                                 
                            </div>
                            <hr>
                            <div class="col-lg-2 eventcreate">
                                <label>Biļešu skaits:</label> {{-- Cilvēka rezervētais biļešu skaits --}}
                                <h4>{{ $reservation->Tickets }}</h4>    
                            </div>

                            
                            <div class="col-lg-4 eventcreate">
                                    
                                <label>Sēdvietas:</label>
                                @if($checkedseats == 0)
                                <h6>Pasākums neparedz Sēdvietas</h6>
                                @elseif($reservation->Seats == 0)
                                <h6>Jūs neesat rezervējuši sēdvietas</h6>
                                @elseif($reservation->Seats != 0)
                                <h4>{{ $reservation->Seats }}</h4>
                                @endif
                            </div>

                            <div class="col-lg-4 eventcreate">

                                <label>Galdi:</label>
                                @if($checkedtables == 0)
                                <h6>Pasākums neparedz Galdus</h6>
                                @elseif($reservation->TableSeats == 0)
                                <h6>Jūs neesat rezervējuši sēdvietas pie galdiem</h6>
                                @elseif($reservation->TableSeats != 0)
                                <h5>{{ $reservation->TableSeats }} sēdvieta(s) pie {{ $reservation->TableNr }}. galda</h5>
                                @endif

                            </div>

                            <div class="col-lg-11 eventcreate">

                                <label>Transports:</label>
                                @if($reservation->Transport === 'Patstāvīgi')
                                <h6>Jūs izvēlējāties,ka ieradīsaties uz pasākumu patstāvīgi</h6>
                                @else
                                <h6>Jūs izvēlējāties,ka ieradīsaties uz pasākumu ar autobusu kurš savāks Jūs no reģiona: {{ $reservation->Transport }}</h6>
                                @endif

                            </div>
                        <hr>
                        <div class="col-lg-3 eventcreate">
                            <label>Vārds</label>
                            <h6>{{ $user->First_name }}</h6>
                        </div>
                        <div class="col-lg-3 eventcreate">
                            <label>Uzvārds</label>
                            <h6>{{ $user->Last_name }}</h6>
                        </div>
                        <div class="col-lg-5 eventcreate">
                            <label>e-pasts</label>
                            <h6>{{ $user->email }}</h6>
                        </div>
                        <div class="col-lg-11 eventcreate">
                        
                        @if($myevent->Editable == 1 || Auth::user()->hasRole('Admin'))
                        <a href="{{ route('showreservationedit',$reservation->id) }}" class="btn btn-primary btn-block">Rediģēt pasākumu</a>
                        @endif 
                        </div>
            </div>
        </div>
    </div>
@endsection
                        