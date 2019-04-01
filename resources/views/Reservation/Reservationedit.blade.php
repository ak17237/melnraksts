@extends('welcome')
@section('content')

<div class="container">
    <br>
        <a href="{{ route('showreservation',$reservation->id) }}" class="btn btn-primary back">Back</a>
        <div class="row">
            <div class="col-lg-offset-1 col-lg-11">
                
                <form action="#" method="POST">
                    {{csrf_field()}}    
                        <fieldset>
                        <legend>Manas rezervācijas</legend>
                        @if(session()->has('message'))
                        <div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>{{ session()->get('message') }}</p>
                        </div>
                        @endif
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
                                    <label>Biļešu skaits</label> {{-- Cilvēka rezervētais biļešu skaits --}}
                                    <input type="number" min="1" name='ticketcount' class="count form-control {{ $errors->has('ticketcount') ? ' is-invalid' : '' }}" id="ticketcount" value="{{ $reservation->Tickets }}">
                                    @if ($errors->has('ticketcount'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ticketcount') }}</strong>
                                         </span>
                                     @endif
                            </div>

                            @if($checkedseats != 0) {{-- Ja pasākums neparedz sēdvietas nerāda lauku --}}
                                <div class="col-lg-10 eventcreate">
                                    <div class="radiocontainer eventcreate">
                                        <label class="seats">Sēdvietas</label>
                                        <div class="radio">
                                          <div class="custom-radio control-radio">
                                            <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="Yes"
                                            @if(empty(old('customRadio')) && $reservation->Seats != 0) {{-- Ja vecās vērtības nav un sēdvietas tika rezervētas --}}
                                            checked=""
                                            @elseif(empty(old('customRadio')) && $reservation->Seats == 0) {{-- Ja vecās vērtības nav un sēdvietas nav --}}
                                            @elseif(old('customRadio') == "Yes") {{-- ja vecā vērtība ir YES tad atzīmēt šo radio --}}
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="customRadio1">Yes</label>
                                          </div>
                                          <div class="custom-radio control-radio">
                                            <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="No"
                                            @if(empty(old('customRadio')) && $reservation->Seats == 0) {{-- Ja vecās vērtības nav un sēdvietas netika rezervētas --}}
                                            checked=""
                                            @elseif(empty(old('customRadio')) && $reservation->Seats != 0) {{-- Ja vecās vērtības nav un sēdvietas tika rezervētas --}}
                                            @elseif(old('customRadio') == "No") {{-- ja vecā vērtība ir YES tad atzīmēt šo radio --}}
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="customRadio2">No</label>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 eventcreate">
                                        <label>Sēdvietu skaits</label>
                                        <input type="number" min="1" name='seatnr' id="seatcount" class="count form-control eventseat {{ $errors->has('seatnr') ? ' is-invalid' : '' }}"
                                        @if(empty(old('customRadio')) && $reservation->Seats == 0)
                                        disabled
                                        value=''
                                        @elseif(empty(old('customRadio')) && $reservation->Seats != 0)
                                        value={{ $reservation->Seats }}
                                        @elseif(old('customRadio') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                        disabled
                                        value=''
                                        @else {{-- ja bija YES,tad ielikt vērtību --}}
                                        value="{{ old('seatnr') }}"
                                        @endif>
                                        @if ($errors->has('seatnr'))
                                            <span class="invalid-feedback alertseatnr" role="alert">
                                            <strong>{{ $errors->first('seatnr') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @else
                                    @endif
                                    @if($checkedtables != 0) {{-- Ja pasākums neparedz galdus nerāda lauku --}}
                                    <div class="radiocontainer eventcreate">
                                            <label class="seats">Galdi</label>
                                            <div class="radio">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" value="Yes" 
                                                @if(empty(old('inlineDefaultRadiosExample')) && $reservation->TableSeats != 0) {{-- Ja vecās vērtības nav un sēdvietas tika rezervētas --}}
                                                checked=""
                                                @elseif(empty(old('inlineDefaultRadiosExample')) && $reservation->TableSeats == 0) {{-- Ja vecās vērtības nav un sēdvietas nav --}}
                                                @elseif(old('inlineDefaultRadiosExample') == "Yes") {{-- ja vecā vērtība ir YES tad atzīmēt šo radio --}}
                                                checked="" 
                                                @endif>
                                                <label class="custom-control-label" for="defaultInline1">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample" value="No"
                                            @if(empty(old('inlineDefaultRadiosExample')) && $reservation->TableSeats == 0) {{-- Ja vecās vērtības nav un sēdvietas netika rezervētas --}}
                                            checked=""
                                            @elseif(empty(old('inlineDefaultRadiosExample')) && $reservation->TableSeats != 0) {{-- Ja vecās vērtības nav un sēdvietas tika rezervētas --}}
                                            @elseif(old('inlineDefaultRadiosExample') == "No") {{-- ja vecā vērtība ir YES tad atzīmēt šo radio --}}
                                            checked="" 
                                            @endif>
                                                <label class="custom-control-label" for="defaultInline2">No</label>
                                            </div>
                                            </div>
                                        </div>
    
                                        <div class="col-lg-3 eventcreate">
                                            <label>Sēdvietas pie galda - </label>
                                            <select name="tablenr" id="tablenr">
                                                @for ($i = 1; $i <= $myevent->Tablenumber; $i++)
                                                <option data-descr="{{ $myevent->Seatsontablenumber - tableSeats($myevent->id,$i) . '/' . $myevent->Seatsontablenumber }}"value="{{ $i }}"
                                                        @if($i == old('tablenr')) selected @endif
                                                        @if($myevent->Seatsontablenumber - tableSeats($myevent->id,$i) == 0) disabled @endif @if($reservation->TableNr == $i) selected @endif>{{ $i }}</option>
                                                <p id="tooltipBox" class="col-sm-6"></p>
                                                @endfor
                                                
                                            </select>
                                            
                                            <input type="number" min="1" name='tablecount' id="tablecount" class="count form-control eventtable {{ $errors->has('tablecount') ? ' is-invalid' : '' }}" 
                                            @if(empty(old('inlineDefaultRadiosExample')) && $reservation->TableSeats == 0)
                                        disabled
                                        value=''
                                        @elseif(empty(old('inlineDefaultRadiosExample')) && $reservation->TableSeats != 0)
                                        value={{ $reservation->TableSeats }}
                                        @elseif(old('inlineDefaultRadiosExample') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                        disabled
                                        value=''
                                        @else {{-- ja bija YES,tad ielikt vērtību --}}
                                        value="{{ old('seatnr') }}"
                                        @endif>
                                            @if ($errors->has('tablecount'))
                                                <span class="invalid-feedback alerttablenr" role="alert">
                                                <strong>{{ $errors->first('tablecount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="eventcreate ticketinfo question"><img class="questiontooltip" id="tabletooltip" src="{{ asset('questionmark.png') }}" width="35" height="35"></div>
                                        @else
                                        @endif
                            </div>
                            <div class="radiocontainer eventcreate">
                                <label class="ticketcount">Ieradīšos patstāvīgi</label>
                            <div class="radio">
                            <div class="custom-radio control-radio">
                                <input type="radio" id="Radio1" name="TransportRadio" class="custom-control-input" value="Yes"   
                                @if(empty(old('TransportRadio')) && $reservation->Transport === 'Patstāvīgi') {{-- Ja vecās vērtības nav un bija atzīmēts Patstāvīgi lauks --}}
                                checked=""
                                @elseif(empty(old('TransportRadio')) && $reservation->Transport !== 'Patstāvīgi') {{-- Ja vecās vērtības nav un nebija atzīmēts patstāvīgi lauks --}}
                                @elseif(old('TransportRadio') == "Yes") {{-- ja vecā vērtība ir YES tad atzīmēt šo radio --}}
                                checked="" 
                                @endif>
                                <label class="custom-control-label" for="Radio1">Yes</label>
                            </div>
                            <div class="custom-radio control-radio">
                                <input type="radio" id="Radio2" name="TransportRadio" class="custom-control-input" value="No"
                                @if(empty(old('TransportRadio')) && $reservation->Transport !== 'Patstāvīgi') {{-- Ja vecās vērtības nav un nebija atzīmēts Patstāvīgi lauks --}}
                                checked=""
                                @elseif(empty(old('TransportRadio')) && $reservation->Transport === 'Patstāvīgi') {{-- Ja vecās vērtības nav un bija atzīmēts patstāvīgi lauks --}}
                                @elseif(old('TransportRadio') == "No") {{-- ja vecā vērtība ir YES tad atzīmēt šo radio --}}
                                checked="" 
                                @endif>
                                <label class="custom-control-label" for="Radio2">No</label>
                            </div>
                            </div>
                        </div>

                        <div class="col-lg-4 eventcreate">
                                <label>Transports no reģiona</label>
                            <select name="transport" class="form-control" id="transport" value="Daugavpils"
                            @if(empty(old('TransportRadio')) && $reservation->Transport === 'Patstāvīgi') {{-- Ja vecās vērtības nav un bija atzīmēts patstāvīgi lauks --}}
                            disabled
                            @elseif(empty(old('TransportRadio')) && $reservation->Transport !== 'Patstāvīgi') {{-- Ja vecās vērtības nav un nebija atzīmēts patstāvīgi lauks --}}
                            @elseif(old('TransportRadio') == "Yes") {{-- ja vecā vērtība ir YES tad atsēgt šo dropdown --}}
                            disabled 
                            @endif>
                                <option value="Empty"></option>
                                
                                <option value="Riga" @if(empty(old('TransportRadio')) && $reservation->Transport === 'Riga') selected @elseif("Riga" == old('transport')) selected @endif>Riga</option>
                                <option value="Liepaja" @if(empty(old('TransportRadio')) && $reservation->Transport === 'Liepaja') selected @elseif("Liepaja" == old('transport')) selected @endif>Liepaja</option>
                                <option value="Daugavpils" @if(empty(old('TransportRadio')) && $reservation->Transport === 'Daugavpils') selected @elseif("Daugavpils" == old('transport')) selected @endif>Daugavpils</option>
                            </select>
                            
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
                            @if (Auth::user()->hasRole('Admin'))
                            <span class="eventcreatebutton"><button type="submit" class="btn btn-primary formbtn" name="edit" value="edit">Saglabāt izmaiņas</button></span>
                            {!! Form::open(['method' => 'DELETE','route' => ['reservationdelete',$myevent->id]]) !!}
                            <span class="deletebtn"><button onclick="return confirm('Vai esi pārliecināts?')" type="submit" 
                                class="btn btn-danger formbtn" name="delete" value="delete">Dzēst rezervāciju</button></span>
                            {!! Form::close() !!}
                            @elseif (Auth::user()->hasRole('User'))
                            <span class="eventcreatebutton"><button type="submit" class="btn btn-primary formbtn" name="edit" value="edit">Saglabāt izmaiņas</button></span>
                            @endif
                        </div>

                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
                        