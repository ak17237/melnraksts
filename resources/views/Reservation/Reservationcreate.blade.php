@extends('welcome')
@section('content')

<div class="container">
    <br>
        <a href="/" class="btn btn-primary back">Back</a>
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
                    <p>{{ $myevent->Description }}</p>
                </div>
                @if($ticketinfo == 0 && $myevent->Tickets != -999)
                <h3>Biļetes ir beigušās</h3>
                @else
                <form action="{{ route('reservationcreate',$myevent->id) }}" method="POST">
                    {{csrf_field()}}    
                        <fieldset>
                        <legend>Rezervēt pasākumu "{{ $myevent->Title }}"</legend>
                        @if(session()->has('message')) {{-- Veiksmīgas rezervācijas paziņojums --}}
                                    <div class="alert alert-success">
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                            <div class="col-lg-5 eventcreate">
                                <label>Title</label> {{-- Nosaukums no datu bāzes --}}
                                <input type="text" name='title'  disabled class="form-control" id="title" value="{{ $myevent->Title }}">
                            </div>

                            <div class="col-lg-4 eventcreate">
                                <label>Date from</label>{{-- Datums no datu bāzes --}}
                                <input type="date" name="datefrom"  disabled class="form-control" id="datefrom"  value="{{ $myevent->Datefrom }}">
                            </div>

                            <div class="col-lg-11 eventcreate">
                            <div class="col-lg-8 eventcreate">
                                <label>Biļešu skaits (No tām stāvvietas - <span class="stand-tickets">0</span>)</label> {{-- Cilvēka vēlamais biļešu skaits --}}
                                <input type="number" name='ticketcount' class="count form-control {{ $errors->has('ticketcount') ? ' is-invalid' : '' }}" id="ticketcount" value="{{ old('ticketcount') }}">
                                @if ($errors->has('ticketcount'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ticketcount') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="radiocontainer eventcreate ticketinfo"><span>{{ $ticketinfo }}</span><div class="help-tip"> {{-- Biļešu informācija --}}
                                    <p>{{ '* Atlikušās biļetes no kurām ' . $checkedseats . ' ir sēdvietas un ' . $checkedtables . ' ir galdi,pārējās ir stāvvietas(' 
                                    . $standing . ')' }}</p>
                                </div></div>
                        </div>
                        <div class="col-lg-11 eventcreate">
                                @if($checkedseats != 0) {{-- Ja pasākums neparedz sēdvietas nerāda lauku --}}
                                <div class="radiocontainer eventcreate">
                                    <label class="seats">Seats</label>
                                    <div class="radio">
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="Yes"  
                                        @if(old('customRadio') == "Yes" || empty(old('customRadio'))) {{-- ja vecā vērtība ir YES jeb ja vecās nebija(validācijas nebija),tad atzīmēt šo radio --}}
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="customRadio1">Yes</label>
                                      </div>
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="No"
                                        @if(old('customRadio') == "No") {{-- ja vecā bija NO tikai tad atzīmēt --}}
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="customRadio2">No</label>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 eventcreate">
                                    <label>Seats number</label>
                                    <input type="number" name='seatnr' id="seatcount" class="count form-control eventseat {{ $errors->has('seatnr') ? ' is-invalid' : '' }}"
                                    @if(old('customRadio') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                    disabled
                                    value=''
                                    @else {{-- ja bija YES,tad ielikt vērtību (ja pirmo reizi tad vērtības nevar būt) --}}
                                    value="{{ old('seatnr') }}"
                                    @endif>
                                    @if ($errors->has('seatnr'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('seatnr') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @else
                                @endif
                                @if($checkedtables != 0) {{-- Ja pasākums neparedz galdus nerāda lauku --}}
                                <div class="radiocontainer eventcreate">
                                        <label class="seats">Tables</label>
                                        <div class="radio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" value="Yes" 
                                            @if(old('inlineDefaultRadiosExample') == "Yes" || empty(old('inlineDefaultRadiosExample'))) {{-- ja vecā vērtība ir YES jeb ja vecās nebija(validācijas nebija),tad atzīmēt šo radio --}}
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline1">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample" value="No"
                                            @if(old('inlineDefaultRadiosExample') == "No") {{-- ja vecā bija NO tikai tad atzīmēt --}}
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline2">No</label>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 eventcreate">
                                        <label>Table number</label>
                                        <input type="number" name='tablenr' id="tablecount" class="count form-control eventtable {{ $errors->has('tablenr') ? ' is-invalid' : '' }}" 
                                        @if(old('inlineDefaultRadiosExample') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                        disabled
                                        value=''
                                        @else {{-- ja bija YES,tad ielikt vērtību (ja pirmo reizi tad vērtības nevar būt) --}}
                                        value="{{ old('tablenr') }}"
                                        @endif>
                                        @if ($errors->has('tablenr'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tablenr') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 eventcreate ticketinfo">{{-- Paziņojums cik ir sēdvietas aiz galdiņa --}}
                                        <span>Aiz viena galdiņa ir <span id="tableseats">{{ $myevent->Seatsontablenumber }}</span> sēdvietas</span><div class="help-tip">
                                                <p class="second">Biļešu skaitam jābūt ne mazākam par galdu sēdvietu skaitau,ja pie galda ir 2 sēdvietas,tad rezervējot galdu ir jārezervē 2 biļetes</p>
                                            </div>
                                    </div>
                                    @else
                                    @endif
                        </div>

                                        <div class="radiocontainer eventcreate">
                                                <label class="ticketcount">Ieradīšos patstāvīgi</label>
                                            <div class="radio">
                                            <div class="custom-radio control-radio">
                                                <input type="radio" id="Radio1" name="TransportRadio" class="custom-control-input" value="Yes"   
                                                @if(old('TransportRadio') == "Yes" || empty(old('TransportRadio'))) {{-- ja vecā vērtība ir YES jeb ja vecās nebija(validācijas nebija),tad atzīmēt šo radio --}}
                                                checked="" 
                                                @endif>
                                                <label class="custom-control-label" for="Radio1">Yes</label>
                                            </div>
                                            <div class="custom-radio control-radio">
                                                <input type="radio" id="Radio2" name="TransportRadio" class="custom-control-input" value="No"
                                                @if(old('TransportRadio') == "No") {{-- ja vecā bija NO tikai tad atzīmēt --}}
                                                checked="" 
                                                @endif>
                                                <label class="custom-control-label" for="Radio2">No</label>
                                            </div>
                                            </div>
                                        </div>
        
                                        <div class="col-lg-4 eventcreate">
                                                <label>Transports no reģiona</label>
                                            <select name="transport" class="form-control" id="transport" value="Daugavpils"
                                            @if(old('TransportRadio') == "Yes") {{-- ja vecā bija YES tad atslēgt input un noņemt vērtību --}}
                                            disabled
                                            @endif>
                                                <option value="Empty"></option>
                                                
                                                <option value="Riga" @if("Riga" == old('transport')) selected @endif>Riga</option>
                                                <option value="Liepaja" @if("Liepaja" == old('transport')) selected @endif>Liepaja</option>
                                                <option value="Daugavpils" @if("Daugavpils" == old('transport')) selected @endif>Daugavpils</option>
                                            </select>
                                            
                                        </div> {{-- Pasakaidrojums formai --}}
                                        <div class="col-lg-11 eventcreate ticketinfo">
                                            <span>Sēdvietas nekādā veidā nav saistītas ar sēdvietām pie galda,tās ir neatkarīgās sēdvietas</span>    
                                        </div>



                            <div class="col-lg-8 eventcreate">
                                        <span class="eventcreatebutton"><button type="submit" class="btn btn-primary" name="action" value="create">Rezervēt</button></span>
                            </div>
                        </fieldset>
                </form>
                @endif
        </div>
    </div>
</div>

@endsection