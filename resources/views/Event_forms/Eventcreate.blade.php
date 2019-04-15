@extends('welcome')
@section('content')

<div class="container">
    <br>
        <a href="/" class="btn btn-primary back">Back</a>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-11">
                
                <form action="{{ route('create') }}" method="POST">
                    {{csrf_field()}}    
                        <fieldset>
                        <legend>Create event</legend>
                        @if(session()->has('message'))
                        <div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>{{ session()->get('message') }}</p>
                        </div>
                        @endif
                        @if(session()->get('info') === 'VIP')
                        <div class="alert alert-dismissible alert-primary">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Tika izveidots VIP pasākums!</strong><p class="mb-0">Linku uz izveidoto VIP pasākumu var atrast slaiderī pie pasākuma,rediģēšanas formā un pie pasākuma apskata</p>
                        </div>
                        @endif
                            <div class="col-lg-5 eventcreate">
                                <label>Title</label>
                                <input type="text" name='title' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" value="{{ old('title') }}">
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-3 eventcreate">
                                <label>Date from</label>
                                <input type="date" name="datefrom" class="form-control {{ $errors->has('datefrom') ? ' is-invalid' : '' }}" id="datefrom"  
                                @if (empty(old('datefrom'))) {{-- ja nav vecās vērtības izvadīt apstrādātro datumu kontrolierī(ja validācijas nebija) --}}
                                value="{{ $date }}"
                                @else {{-- ja ir,tad izvadīt veco vērtību(pēc validācijas) --}}
                                value="{{ old('datefrom') }}"
                                @endif >
                                @if ($errors->has('datefrom'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('datefrom') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-3 eventcreate">
                                <label>Date to</label>
                                <input type="date" name="dateto" class="form-control {{ $errors->has('dateto') ? ' is-invalid' : '' }}" id="dateto" 
                                @if (empty(old('dateto')))
                                value="{{ $date }}"
                                @else
                                value="{{ old('dateto') }}"
                                @endif >
                                @if ($errors->has('dateto'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('dateto') }}</strong>
                                     </span>
                                 @endif
                            </div>


                            <div class="col-lg-11 eventcreate">
                                <label>Address</label>
                                <input type="text" name='address' class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="eventaddress" value="{{ old('address') }}">
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-11 eventcreate">
                                <div class="col-lg-4 radiocontainer eventcreate">
                                        <label class="ticketcount">Biļešu skaits</label>
                                    <div class="radio">
                                    <div class="custom-radio control-radio">
                                        <input type="radio" id="Radio1" name="Radio" class="custom-control-input" value="Yes" 
                                        @if(old('Radio') == "Yes" || empty(old('Radio'))) {{-- ja vecā vērtība ir YES jeb ja vecās nebija(validācijas nebija),tad atzīmēt šo radio --}}
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="Radio1">Ierobežots</label>
                                    </div>
                                    <div class="custom-radio control-radio">
                                        <input type="radio" id="Radio2" name="Radio" class="custom-control-input" value="No"
                                        @if(old('Radio') == "No") {{-- ja vecā bija NO tikai tad atzīmēt --}}
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="Radio2">Neierobežots</label>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 eventcreate">
                                        <label>Skaits</label>
                                    <input type="number" name='ticketcount' class="form-control tickets {{ $errors->has('ticketcount') ? ' is-invalid' : '' }}" id="eventaddress" 
                                    @if(old('Radio') == "No") {{-- ja vecā bija NO tad atslēgt input un noņemt vērtību --}}
                                    disabled
                                    value=''
                                    @else {{-- ja bija YES,tad ielikt vērtību (ja pirmo reizi tad vērtības nevar būt) --}}
                                    value="{{ old('ticketcount') }}"
                                    @endif>
                                    @if ($errors->has('ticketcount'))
                                        <span class="invalid-feedback alertticketcount" role="alert">
                                        <strong>{{ $errors->first('ticketcount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                                <div class="radiocontainer eventcreate">
                                    <label class="seats">Seats</label>
                                    <div class="radio">
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="Yes"  
                                        @if(old('customRadio') == "Yes" || empty(old('customRadio'))) 
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="customRadio1">Yes</label>
                                      </div>
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="No"
                                        @if(old('customRadio') == "No") 
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="customRadio2">No</label>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 eventcreate">
                                    <label>Seats number</label>
                                    <input type="number" name='seatnr' class="form-control eventseat {{ $errors->has('seatnr') ? ' is-invalid' : '' }}" 
                                    @if(old('customRadio') == "No")
                                    disabled
                                    value=''
                                    @else
                                    value="{{ old('seatnr') }}"
                                    @endif>
                                    @if ($errors->has('seatnr'))
                                        <span class="invalid-feedback alertseatnr" role="alert">
                                        <strong>{{ $errors->first('seatnr') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="radiocontainer eventcreate">
                                        <label class="seats">Tables</label>
                                        <div class="radio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" value="Yes" 
                                            @if(old('inlineDefaultRadiosExample') == "Yes" || empty(old('inlineDefaultRadiosExample'))) 
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline1">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample" value="No" 
                                            @if(old('inlineDefaultRadiosExample') == "No") 
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline2">No</label>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 eventcreate">
                                        <label>Table number</label>
                                        <input type="number" name='tablenr' class="form-control eventtable {{ $errors->has('tablenr') ? ' is-invalid' : '' }}" 
                                        @if(old('inlineDefaultRadiosExample') == "No")
                                        disabled
                                        value=''
                                        @else
                                        value="{{ old('tablenr') }}"
                                        @endif>
                                        @if ($errors->has('tablenr'))
                                            <span class="invalid-feedback alerttablenr" role="alert">
                                            <strong>{{ $errors->first('tablenr') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-2 eventcreate">
                                        <label>Seats on table</label>
                                        <input type="number" name='seatsontablenr' class="form-control eventtable {{ $errors->has('seatsontablenr') ? ' is-invalid' : '' }}" 
                                        @if(old('inlineDefaultRadiosExample') == "No")
                                        disabled
                                        value=''
                                        @else
                                        value="{{ old('seatsontablenr') }}"
                                        @endif>
                                        @if ($errors->has('seatsontablenr'))
                                            <span class="invalid-feedback alerttablenr" role="alert">
                                            <strong>{{ $errors->first('seatsontablenr') }}</strong>
                                            </span>
                                        @endif
                                    </div>



                            <div class=" col-lg-11 eventcreate">
                                <label>Event anotation</label>
                                <input type="text" name='anotation' class="form-control {{ $errors->has('anotation') ? ' is-invalid' : '' }}" id="eventanotation" value="{{ old('anotation') }}">
                                @if ($errors->has('anotation'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('anotation') }}</strong>
                                    </span>
                                @endif
                            </div>

                        
                            <div class=" col-lg-11 eventcreate">
                                <label>Event description</label>
                                <textarea class="form-control" name='description' id="eventdescription" rows="3">{{ old('description') }}</textarea>
                            </div>
                            
                                <div class="custom-control custom-switch col-lg-11 eventcreate">
                                    <div class="col-lg-2 eventcreate">
                                  <input type="checkbox" class="custom-control-input" id="customSwitch1" name="vipswitch" {{ old('vipswitch') ? 'checked' : '' }}>
                                  <label class="custom-control-label" for="customSwitch1">VIP pasākums</label>
                                </div>
                                <div class="col-lg-4 eventcreate">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch2" name="editableswitch" {{ old('editableswitch') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitch2">Rediģējamas rezervācijas</label> 
                                </div>

                            <div class="col-lg-11 eventcreate">
                                        <span class="eventcreatebutton"><button type="submit" class="btn btn-primary" name="action" value="create">Create</button></span>
                                        <span class="eventcreatebutton"><button type="submit" class="btn btn-primary" name="action" value="save">Save</button></span>
                            </div>
                        </fieldset>
                </form>
                
        </div>
    </div>
</div>

@endsection