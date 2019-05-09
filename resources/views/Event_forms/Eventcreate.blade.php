@extends('welcome')
@section('content')

<div class="container">
        <a href="javascript:window.location=document.referrer;" class="btn btn-primary back">Atpakaļ</a>
    <br>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-11 center">
                
                <form action="{{ route('create') }}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}    
                        <fieldset>
                        <legend class="eventcreate smalltitle m-b-md">Izveidot pasākumu</legend><br><br><br>
                        @if(session()->has('message'))
                        <div class="alert alert-dismissible alert-success" style="margin-top: 79px;margin-bottom: 20px;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p class="mb-0">{{ session()->get('message') }}</p>
                        </div>
                        @endif
                        @if(session()->get('info') === 'VIP')
                        <div class="alert alert-dismissible alert-primary">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Tika izveidots VIP pasākums!</strong><p class="mb-0">Linku uz izveidoto VIP pasākumu var atrast slaiderī pie pasākuma,rediģēšanas formā un pie pasākuma apskata</p>
                        </div>
                        @endif
                            <div class="col-lg-6 eventcreate">
                                <label>Nosaukums</label>
                                <input type="text" name='title' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" value="{{ old('title') }}">
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-3 eventcreate">
                                <label>Datums no</label>
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
                                <label>Datums līdz</label>
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


                            <div class="col-lg-12 eventcreate">
                                <label>Adrese</label>
                                <input type="text" name='address' class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="eventaddress" value="{{ old('address') }}">
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-12 eventcreate">
                                <div class="col-lg-4 radiocontainer eventcreate" style="padding-left: 0;">
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
                                    <label class="seats">Sēdvietas</label>
                                    <div class="radio">
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="Yes"  
                                        @if(old('customRadio') == "Yes" || empty(old('customRadio'))) 
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="customRadio1">Jā</label>
                                      </div>
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="No"
                                        @if(old('customRadio') == "No") 
                                        checked="" 
                                        @endif>
                                        <label class="custom-control-label" for="customRadio2">Nē</label>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 eventcreate">
                                    <label>Sēdvietu skaits</label>
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
                                        <label class="seats">Galdi</label>
                                        <div class="radio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" value="Yes" 
                                            @if(old('inlineDefaultRadiosExample') == "Yes" || empty(old('inlineDefaultRadiosExample'))) 
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline1">Jā</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample" value="No" 
                                            @if(old('inlineDefaultRadiosExample') == "No") 
                                            checked="" 
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline2">Nē</label>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 eventcreate">
                                        <label>Galdu skaits</label>
                                        <input type="number" name='tablenr' id="eventtable" class="form-control eventtable {{ $errors->has('tablenr') ? ' is-invalid' : '' }}" 
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
                                        <label>Sēdvietas pie galda</label>
                                        <input type="number" name='seatsontablenr' id="seatsontable" class="form-control eventtable {{ $errors->has('seatsontablenr') ? ' is-invalid' : '' }}" 
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



                            <div class=" col-lg-12 eventcreate">
                                <label>Pasākuma anotācija</label>
                                <input type="text" name='anotation' class="form-control {{ $errors->has('anotation') ? ' is-invalid' : '' }}" id="eventanotation" value="{{ old('anotation') }}">
                                @if ($errors->has('anotation'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('anotation') }}</strong>
                                    </span>
                                @endif
                            </div>

                        
                            <div class=" col-lg-12 eventcreate">
                                <label>Pasākuma apraksts</label>
                                <textarea class="form-control" name='description' id="eventdescription" rows="3">{{ old('description') }}</textarea>
                            </div>
                            
                                <div class="custom-control custom-switch col-lg-12 eventcreate">
                                    <div class="col-lg-2 eventcreate eventswitchs">
                                  <input type="checkbox" class="custom-control-input" id="customSwitch1" name="vipswitch" {{ old('vipswitch') ? 'checked' : '' }}>
                                  <label class="custom-control-label" for="customSwitch1">VIP pasākums</label>
                                </div>
                                <div class="col-lg-3 eventcreate">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch2" name="editableswitch" {{ old('editableswitch') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitch2">Rediģējamas rezervācijas</label> 
                                    <i class="far fa-question-circle" id="reserveditabletooltip"></i>
                                    <div class="questiontooltip"></div>
                                </div>
                                <div class="col-lg-6 addphoto">
                                    <label class="addphotolabel">Pievienot attēlu</label>
                                    <div class="col-lg-5 uploadphoto phooto">
                                        <input type="file" name="file" class="custom-file-input {{ $errors->has('file') ? ' is-invalid' : '' }}" id="inputGroupFile02">
                                        @if ($errors->has('file'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                        @endif
                                        <label class="custom-file-label {{ $errors->has('file') ? ' is-invalid' : '' }}" id="filename" for="inputGroupFile02">Izvēlēties failu</label>
                                            
                                    </div>
                                </div>
                                <div class="col-lg-7 eventcreate">
                                        <label class="addphotolabel long-label">Pievienot pdf pielikumu</label>
                                        <label class="addphotolabel short-label">Pievienot pdf</label>
                                        <div class="col-lg-5 uploadpdf phooto">
                                            <input type="file" name="pdffile[]" multiple class="custom-file-input {{ $errors->has('pdffile.*') ? ' is-invalid' : '' }}" id="inputGroupFile01">
                                            @if ($errors->has('pdffile.*'))
                                            <span class="invalid-feedback" role="alert" style="white-space: normal;">
                                            <strong>{{ $errors->first('pdffile.*') }}</strong>
                                            </span>
                                            @endif
                                            <label class="custom-file-label {{ $errors->has('pdffile') ? ' is-invalid' : '' }}" id="pdffilename" for="inputGroupFile01">Izvēlēties failu</label>
                                                
                                        </div>
                                    </div>

                            <div class="col-lg-12 eventcreate">
                                        <div class="eventcreatebutton right ecb"><button type="submit" class="btn btn-primary create right" name="action" value="create">Izveidot</button></div>
                                        <div class="eventcreatebutton mr-7-p ecb"><button type="submit" class="btn btn-primary save right" name="action" value="save">Saglabāt</button></div>
                            </div>
                        </fieldset>
                </form>
                
        </div>
    </div>
</div>
@endsection
