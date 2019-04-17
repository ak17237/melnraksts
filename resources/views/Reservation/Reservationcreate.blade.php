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
                    @if(empty($description))
                    <p>Nav apraksta</p>
                    @elseif(linecount($description) > 10 || Storage::disk('public')->has(str_replace(' ', '_',$myevent->Title) . '-' . $myevent->id . '.' . $myevent->imgextension))
                    <a href="{{ route('showevent',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}">Apskatīt aprakstu</a>
                    @else
                    <p>{!! $description !!}</p>
                    @endif
                    
                    
                </div>
                @if($ticketinfo == 0 && $myevent->Tickets != -999)
                <h3>Biļetes ir beigušās</h3>
                @elseif(checkResrvationCount($myevent->id,Auth::user()->email) >= 2) 
                <h3>Jūs pasūtījāt maksimāli pieļaujamo biļešu skaitu uz lietotāju šajā pasākumā</h3>
                @else
                <form action="{{ route('reservationcreate',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}" method="POST">
                    {{csrf_field()}}    
                        <fieldset>
                        <legend>Rezervēt pasākumu "{{ $myevent->Title }}"</legend>
                        @if(session()->has('message'))
                        <div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>{{ session()->get('message') }}</p>
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

                            <div class="col-lg-8 eventcreate">
                                <label>Biļešu skaits (No tām stāvvietas - <span class="stand-tickets">0</span>) MAX 2</label> {{-- Cilvēka vēlamais biļešu skaits --}}
                                <input type="number" min="1" name='tickets' class="count form-control {{ $errors->has('tickets') ? ' is-invalid' : '' }}" id="tickets" value="{{ old('tickets') }}">
                                @if ($errors->has('tickets'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tickets') }}</strong>
                                     </span>
                                 @endif
                                </div>
                            <div class="col-lg-3 radiocontainer eventcreate ticketinfo"><span>{{ $ticketinfo }}</span><div class="help-tip"> {{-- Biļešu informācija --}}
                                    <p>{{ '* Atlikušās biļetes no kurām ' . $checkedseats . ' ir sēdvietas un ' . $checkedtables . ' ir sēdvietas pie galdiem,pārējās ir stāvvietas(' 
                                    . $standing . ')' }}</p>
                                </div></div>
                                @if($checkedseats != 0) {{-- Ja pasākums neparedz sēdvietas nerāda lauku --}}
                                <div class="col-lg-11 eventcreate">
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
                                        <input type="number" min="1" name='seatnr' id="seatcount" class="count form-control eventseat {{ $errors->has('seatnr') ? ' is-invalid' : '' }}"
                                        @if(old('customRadio') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                        disabled
                                        value=''
                                        @else {{-- ja bija YES,tad ielikt vērtību (ja pirmo reizi tad vērtības nevar būt) --}}
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
                                            <label>Seats on table</label>
                                            <select name="tablenr" id="tablenr">
                                                @for ($i = 1; $i <= $myevent->Tablenumber; $i++)
                                                <option data-descr="{{ $myevent->Seatsontablenumber - tableSeats($myevent->id,$i) . '/' . $myevent->Seatsontablenumber }}"value="{{ $i }}"
                                                        @if($i == old('tablenr')) selected @endif
                                                        @if($myevent->Seatsontablenumber - tableSeats($myevent->id,$i) == 0) disabled @endif>{{ $i }}</option>
                                                <p id="tooltipBox" class="col-sm-6"></p>
                                                @endfor
                                                
                                            </select>
                                            
                                            <input type="number" min="1" name='tablecount' id="tablecount" class="count form-control eventtable {{ $errors->has('tablecount') ? ' is-invalid' : '' }}" 
                                            @if(old('inlineDefaultRadiosExample') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                            disabled
                                            value=''
                                            @else {{-- ja bija YES,tad ielikt vērtību (ja pirmo reizi tad vērtības nevar būt) --}}
                                            value="{{ old('tablecount') }}"
                                            @endif>
                                            @if ($errors->has('tablecount'))
                                                <span class="invalid-feedback alerttablenr" role="alert">
                                                <strong>{{ $errors->first('tablecount') }}</strong>
                                                </span>
                                            @endif
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