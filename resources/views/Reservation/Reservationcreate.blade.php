@extends('welcome')
@section('content')

<div class="container">
    <a href="javascript:window.location=document.referrer;" class="btn btn-primary back">Atpakaļ</a>
    <br>
    <div class="row">
        <div class="col-lg-offset-3 col-lg-11 center">
            <div class="content">
                <h2 class="content">{{ $myevent->Title }}</h2>
                @if(geteventdate($myevent->Datefrom) == geteventdate($myevent->Dateto)) {{-- Datuma korektra izvade --}}
                    <p class="content">{{ geteventdate($myevent->Datefrom) }}</p>
                @else
                    <p class="content">{{ geteventdate($myevent->Datefrom) . '-' . geteventdate($myevent->Dateto) }}</p>
                @endif
                <h6 class="content"><i>{{ $myevent->Address }}</i></h6>
                @if(empty($description))
                    <p class="content">Nav apraksta</p>
                @elseif(linecount($description) > 10 || Storage::disk('public')->has(str_replace(' ', '_',$myevent->Title) . '-' . $myevent->id . '.' . $myevent->imgextension))
                    <a href="{{ route('showevent',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}" class="content">Apskatīt aprakstu</a>
                @else
                    <p class="content">{!! $description !!}</p>
                @endif
            </div>
            @if($ticketinfo == 0 && $myevent->Tickets != -999)
                <h3 style="text-align: -webkit-center;">Biļetes ir beigušās</h3>
            @elseif(checkResrvationCount($myevent->id,Auth::user()->email) >= 2) 
                <h3 style="text-align: center;"><strong>Jūs pasūtījāt maksimāli pieļaujamo biļešu skaitu uz lietotāju šajā pasākumā</strong></h3>
            @else
                <form action="{{ route('reservationcreate',['id' => $myevent->id, 'extension' => $myevent->linkcode]) }}" method="POST">
                    {{csrf_field()}}    
                    <fieldset>
                        <legend class="eventcreate">Rezervēt pasākumu "{{ $myevent->Title }}"</legend>
                        @if(session()->has('message'))
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <p>{{ session()->get('message') }}</p>
                            </div>
                        @endif
                        @if(Auth::check() && Auth::user()->hasRole('Admin'))
                            <div class="custom-control custom-switch col-lg-2 eventcreate manualreserv">
                                <input type="hidden" name="manualreserv" value="off" />
                                <input type="checkbox" class="custom-control-input" id="customSwitch1" name="manualreserv" 
                                @if(old('manualreserv') == "on")
                                checked 
                                @endif>
                                <label class="custom-control-label" for="customSwitch1">Rezervēt lietotāju</label>
                            </div>
                        @endif
                            <div class="col-lg-4 eventcreate manualreservdata" style="display: none;">
                                <label>E-pasts</label>
                                <input type="email" name='email' class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            
                        <div class="col-lg-8 eventcreate ml-7-p">
                            <label>Biļešu skaits (No tām stāvvietas - <span class="stand-tickets">0</span>) MAX 2</label> {{-- Cilvēka vēlamais biļešu skaits --}}
                            <input type="number" min="1" name='tickets' class="count form-control {{ $errors->has('tickets') ? ' is-invalid' : '' }}" id="tickets" value="{{ old('tickets') }}">
                            @if ($errors->has('tickets'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('tickets') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-lg-2 eventcreate ticketinfo">
                            <span id="ticketinfo">{{ $ticketinfo }}</span> 
                            @if($ticketinfo === "Neierobežots" && $checkedseats == 0 && $checkedtables == 0)
                            @else
                                <span style="display:none;" id="chseat">{{ $checkedseats }}</span>
                                <span style="display:none;" id="chtable">{{ $checkedtables }}</span>
                                <span style="display:none;" id="chstand">{{ $standing }}</span>
                                <i class="far fa-question-circle" id="tickettooltip"></i>
                                <div class="questiontooltip"></div>
                            @endif
                        </div>
                        @if($checkedseats != 0) {{-- Ja pasākums neparedz sēdvietas nerāda lauku --}}
                            <div class="col-lg-11 eventcreate ml-7-p">
                                <div class="radiocontainer eventcreate" style="padding-left: 0;">
                                    <label class="seats">Sēdvietas</label>
                                    <div class="radio">
                                        <div class="custom-radio control-radio">
                                            <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="Yes"   
                                            @if(old('customRadio') == "Yes" || empty(old('customRadio'))) {{-- ja vecā vērtība ir YES jeb ja vecās nebija(validācijas nebija),tad atzīmēt šo radio --}}
                                                checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="customRadio1">Jā</label>
                                        </div>
                                        <div class="custom-radio control-radio">
                                            <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="No"
                                            @if(old('customRadio') == "No") {{-- ja vecā bija NO tikai tad atzīmēt --}}
                                                checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="customRadio2">Nē</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 eventcreate">
                                    <label>Sēdvietu skaits</label>
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
                                    @if($checkedseats == 0)<div class="ml-7-p" style="float: left;width: 100%;"> @endif
                                    <div class="radiocontainer eventcreate">
                                        <label class="seats">Galdi</label>
                                        <div class="radio">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" value="Yes" 
                                                @if(old('inlineDefaultRadiosExample') == "Yes" || empty(old('inlineDefaultRadiosExample'))) {{-- ja vecā vērtība ir YES jeb ja vecās nebija(validācijas nebija),tad atzīmēt šo radio --}}
                                                    checked="" 
                                                @endif>
                                                <label class="custom-control-label" for="defaultInline1">Jā</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample" value="No"
                                                @if(old('inlineDefaultRadiosExample') == "No") {{-- ja vecā bija NO tikai tad atzīmēt --}}
                                                    checked="" 
                                                @endif>
                                                <label class="custom-control-label" for="defaultInline2">Nē</label>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="col-lg-2 eventcreate" style="min-width: 126px;max-width: 29%;">
                                        <label>Sēdvietas pie galda</label>
                                        <select name="tablenr" id="tablenr">
                                            @for ($i = 1; $i <= $myevent->Tablenumber; $i++)
                                                <option data-descr="{{ $myevent->Seatsontablenumber - tableSeats($myevent->id,$i) . '/' . $myevent->Seatsontablenumber }}"value="{{ $i }}"
                                                    @if($i == old('tablenr')) selected @endif
                                                    @if($myevent->Seatsontablenumber - tableSeats($myevent->id,$i) == 0) disabled @endif>{{ $i }}
                                                </option>
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
                                            <span class="invalid-feedback alerttablecount" role="alert">
                                            <strong>{{ $errors->first('tablecount') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @endif
                                     @if($checkedseats != 0 && $checkedtables != 0) </div> @endif{{-- Elementu novietošanai,lai lapas elementi nenobīdītos dažādos lapas variantos--}}
                                @if($checkedseats == 0 && $checkedtables == 0)<div style="float: left;width: -webkit-fill-available;">@endif
                                     <div class="radiocontainer eventcreate 
                                     @if(($checkedseats != 0 && $checkedtables == 0) || ($checkedseats == 0 && $checkedtables != 0)) @else ml-7-p @endif"> {{-- Ja ir sēdvietas,šis lauks ir vienār dinā un nevajag lai bīdās --}}
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
                                            
                                    </div> 
                                    @if($checkedseats == 0 && $checkedtables == 0)</div>@endif {{-- Stilizācijai,ja nav ne lauku ar galdiem ne sēdvietām lai pareizi bīdītos --}}
                                    @if(($checkedseats != 0 && $checkedtables == 0) || ($checkedseats == 0 && $checkedtables != 0))</div>@endif {{-- Ja ir tikai sēdveitas vai tikai galdi,lai pareizi bīdītos --}}
                                {{-- Pasakaidrojums formai --}}
                                    <div class="col-lg-11 eventcreate ticketinfo ml-7-p">
                                        <span>Sēdvietas nekādā veidā nav saistītas ar sēdvietām pie galda,tās ir neatkarīgās sēdvietas</span>    
                                    </div>



                            <div class="col-lg-12 eventcreate reservationdiv">
                                <button type="submit" class="btn btn-primary reserv reservationrecord" name="action" value="create">Rezervēt</button>
                            </div>
                        </fieldset>
                </form>
                @endif
        </div>
    </div>
</div>

@endsection