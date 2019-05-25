@extends('welcome')
@section('PageTitle','Rezervācijas pārskats')
@section('content')

<div class="container">
    <br>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-11 center">

                    @if(session()->has('message'))
                    <div class="alert alert-dismissible alert-success reservation-message">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <p class="mb-0">{{ session()->get('message') }}</p>
                    </div>
                    @endif

                        <legend class="eventcreate smalltitle m-b-md ml-3-p">Manas rezervācijas</legend>
                            <div class="col-lg-5 eventcreate ml-3-p">
                                <label>Nosaukums</label>
                                <h4><strong>{{ $myevent->Title }}</strong></h4>
                            </div>                   
                    
                            <div class="col-lg-3 eventcreate">
                                <label>
                                    @if(geteventdate($myevent->Datefrom) != geteventdate($myevent->Dateto))
                                    Datums no
                                    @else Datums
                                    @endif
                                </label>
                                <h5><strong>{{ geteventdate($myevent->Datefrom) }}</h5>
                            </div>
                            @if(geteventdate($myevent->Datefrom) != geteventdate($myevent->Dateto)) {{-- Datuma korektra izvade --}}
                            <div class="col-lg-3 eventcreate">
                                <label>Datums līdz</label>
                                <h5><strong>{{ geteventdate($myevent->Dateto) }}</strong></h5>
                            </div>
                            @endif


                            <div class="col-lg-11 eventcreate ml-3-p">
                                <label>Aderese</label>
                                <h5><strong>{{ $myevent->Address }}</strong></h5>
                                 
                            </div>
                            <hr class="ml-3-p">
                            <div class="col-lg-2 eventcreate ml-3-p">
                                <label>Biļešu skaits:</label> {{-- Cilvēka rezervētais biļešu skaits --}}
                                <h4><strong>{{ $reservation->Tickets }}</strong></h4>    
                            </div>

                            
                            <div class="col-lg-4 eventcreate">
                                    
                                <label>Sēdvietas:</label>
                                @if($checkedseats == 0)
                                <h5><strong>Pasākums neparedz Sēdvietas</strong></h5>
                                @elseif($reservation->Seats == 0)
                                <h5><strong>Jūs neesat rezervējuši sēdvietas</strong></h5>
                                @elseif($reservation->Seats != 0)
                                <h4><strong>{{ $reservation->Seats }}</strong></h4>
                                @endif
                            </div>

                            <div class="col-lg-4 eventcreate">

                                <label>Galdi:</label>
                                @if($checkedtables == 0)
                                <h5><strong>Pasākums neparedz Galdus</strong></h5>
                                @elseif($reservation->TableSeats == 0)
                                <h5><strong>Jūs neesat rezervējuši sēdvietas pie galdiem</strong></h5>
                                @elseif($reservation->TableSeats != 0)
                                <h5><strong>{{ $reservation->TableSeats }} sēdvieta(s) pie {{ $reservation->TableNr }}. galda</strong></h5>
                                @endif

                            </div>

                            <div class="col-lg-11 eventcreate ml-3-p">

                                <label>Transports:</label>
                                @if($reservation->Transport === 'Patstāvīgi')
                                <h5><strong>Jūs izvēlējāties,ka ieradīsaties uz pasākumu patstāvīgi</strong></h5>
                                @else
                                <h5><strong>Jūs izvēlējāties,ka ieradīsaties uz pasākumu ar autobusu kurš savāks Jūs no reģiona: {{ $reservation->Transport }}</strong></h5>
                                @endif

                            </div>
                        <hr class="ml-3-p">
                        <div class="col-lg-3 eventcreate ml-3-p">
                            <label>Vārds</label>
                            <h5><strong>{{ $user->First_name }}</strong></h5>
                        </div>
                        <div class="col-lg-3 eventcreate">
                            <label>Uzvārds</label>
                            <h5><strong>{{ $user->Last_name }}</strong></h5>
                        </div>
                        <div class="col-lg-5 eventcreate">
                            <label>e-pasts</label>
                            <h5><strong>{{ $user->email }}</strong></h5>
                        </div>
                        <div class="col-lg-11 eventcreate ml-3-p">
                        
                        @if($myevent->Editable == 1 || Auth::user()->hasRole('Admin'))
                            @if(!checkExpired($reservation->EventID))
                        <a href="{{ route('showreservationedit',$reservation->id) }}" class="btn btn-primary reserv btn-block">Rediģēt rezervāciju</a>
                            @endif
                        @endif 
                        </div>
            </div>
        </div>
    </div>
@endsection
                        