@extends('welcome')
@section('content')
<div class="container">
        <a @if ($myevent->Melnraksts == 0)
                href="/"
            @else
                href="/saved-events-1"
            @endif class="btn btn-primary back">Atpakaļ</a>
            <br>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-11 center">
                
                <form action="{{ route('edit',$myevent->id) }}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}    
                        <fieldset>
                        <legend>
                            <p style="float:left;">Rezervācijas links: </p>
                                <input id="reservlink" class="form-control col-lg-5 eventcreate" value="{{ route('showreservationcreate', ['id' => $myevent->id,'extension' => $myevent->linkcode]) }}">
                                <button id="copybtn" type="button" class="btn btn-secondary clippy eventcreate editcpy">
                                    <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="25" height="25">
                                </button>
                            <p class="eventinfo eventcreate smalltitle m-b-md" style="padding: unset;margin: unset;">Rediģēt pasākumu 
                                @if(strlen($myevent->Title) > 23)
                                @else "{{ $myevent->Title }}"
                                @endif</p>
                                
                        </legend>
                        <div class="alert alert-dismissible alert-warning" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p class="mb-0"><strong>Uzmanību!</strong> Rezervācijas piekļuves url links tiks mainīts</p>
                        </div>
                        @if(session()->has('message'))
                        <div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>{{ session()->get('message') }}</p>
                        </div>
                        @endif
                            <div class="col-lg-6 eventcreate">
                                <label>Nosaukums</label>
                                <input type="text" name='title' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" 
                                @if(empty(old('title'))) {{-- Ja vecās vērtibas nav tad ņemt no datubāzes --}}
                                value="{{ $myevent->Title }}"
                                @else {{-- ja bija tad ievietot veco vērtību --}}
                                value="{{ old('title') }}"
                                @endif>
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-3 eventcreate">
                                <label>Datums no</label>
                                <input type="date" name="datefrom" class="form-control {{ $errors->has('datefrom') ? ' is-invalid' : '' }}" id="datefrom"  
                                @if(empty(old('datefrom')))
                                value="{{ $myevent->Datefrom }}"
                                @else
                                value="{{ old('datefrom') }}"
                                @endif>
                                @if ($errors->has('datefrom'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('datefrom') }}</strong>
                                     </span>
                                 @endif
                            </div>
                            <div class="col-lg-3 eventcreate">
                                <label>Datums līdz</label>
                                <input type="date" name="dateto" class="form-control {{ $errors->has('dateto') ? ' is-invalid' : '' }}" id="dateto" 
                                @if(empty(old('dateto')))
                                value="{{ $myevent->Dateto }}"
                                @else
                                value="{{ old('dateto') }}"
                                @endif>
                                @if ($errors->has('dateto'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('dateto') }}</strong>
                                     </span>
                                 @endif
                            </div>


                            <div class="col-lg-12 eventcreate">
                                <label>Adrese</label>
                                <input type="text" name='address' class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="eventaddress" 
                                @if(empty(old('address')))
                                value="{{ $myevent->Address }}"
                                @else
                                value="{{ old('address') }}"
                                @endif>
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
                                            @if(old('Radio') == "Yes") {{-- Ja vecā ir YES atzīmēt --}}
                                            checked=""
                                            @elseif(old('Radio') != "No") {{-- Ja vecā nav NO(ja atnāc pirmo reizi) --}}
                                                @if ($checkedtickets) {{-- Ņem no datubāzes --}}
                                                checked=""
                                                @endif
                                            @endif>
                                            <label class="custom-control-label" for="Radio1">Ierobežots</label>
                                        </div>
                                        <div class="custom-radio control-radio">
                                            <input type="radio" id="Radio2" name="Radio" class="custom-control-input" value="No"
                                            @if(old('Radio') == "No") {{-- Tāpat kā augstāk --}}
                                            checked=""
                                            @elseif(old('Radio') != "Yes")
                                                @if (!$checkedtickets)
                                                checked=""
                                                @endif
                                            @endif>
                                            <label class="custom-control-label" for="Radio2">Neierobežots</label>
                                        </div>
                                        </div>
                                    </div>
    
                                    <div class="col-lg-4 eventcreate">
                                            <label>Skaits</label>
                                        <input type="number" name='ticketcount' class="form-control tickets {{ $errors->has('ticketcount') ? ' is-invalid' : '' }}" id="eventaddress" 
                                        @if(old('Radio') == "No") {{-- Ja vecā ir NO tad atslēgt un noņemt vērtību --}}
                                        disabled
                                        value=""
                                        @elseif(old('Radio') == "Yes") {{-- Ja vecā YES,tad iedot veco vērtību --}}
                                        value="{{ old('ticketcount') }}"
                                        @else {{-- pretējā gadījumā tas ir null un tad validācijas vēl nebija un var ņem no datubāzes --}}
                                            @if (!$checkedtickets)
                                                disabled
                                                value="" 
                                            @else
                                                value="{{ $myevent->Tickets }}"
                                            @endif
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
                                        @if(old('customRadio') == "Yes") 
                                            checked=""
                                        @elseif(old('customRadio') != "No")
                                            @if ($checkedseats)
                                            checked=""
                                            @endif
                                        @endif>
                                        <label class="custom-control-label" for="customRadio1">Jā</label>
                                      </div>
                                      <div class="custom-radio control-radio">
                                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="No"
                                        @if(old('customRadio') == "No")
                                            checked=""
                                        @elseif(old('customRadio') != "Yes")
                                            @if (!$checkedseats)
                                            checked=""
                                            @endif
                                        @endif>  
                                        <label class="custom-control-label" for="customRadio2">Nē</label>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 eventcreate">
                                    <label>Sēdvietu skaits</label>
                                    <input style="width: 62%;"type="number" name='seatnr' class="form-control eventseat {{ $errors->has('seatnr') ? ' is-invalid' : '' }}"
                                    @if(old('customRadio') == "No")
                                        disabled
                                        value=""
                                    @elseif(old('customRadio') == "Yes")
                                        value="{{ old('seatnr') }}"
                                    @else
                                        @if (!$checkedseats)
                                            disabled
                                            value="" 
                                        @else
                                             value="{{ $myevent->Seatnumber }}"
                                    @endif
                                        @endif>
                                    @if ($errors->has('seatnr'))
                                        <span class="invalid-feedback alertseatnr" role="alert">
                                        <strong>{{ $errors->first('seatnr') }}</strong>
                                        </span>
                                    @elseif($errors->has('customRadio'))
                                        <span class="invalid-feedback alertradio" role="alert">
                                        <strong>{{ $errors->first('customRadio') }}</strong>
                                        </span>
                                    @endif
                                    
                                </div>

                                <div class="radiocontainer eventcreate">
                                        <label class="seats">Galdi</label>
                                        <div class="radio">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample" value="Yes"
                                            @if(old('inlineDefaultRadiosExample') == "Yes") 
                                            checked=""
                                            @elseif(old('inlineDefaultRadiosExample') != "No")
                                                @if ($checkedtables)
                                                checked=""
                                                @endif
                                            @endif>
                                            <label class="custom-control-label" for="defaultInline1">Jā</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="defaultInline2" name="inlineDefaultRadiosExample" value="No"
                                            @if(old('inlineDefaultRadiosExample') == "No")
                                            checked=""
                                            @elseif(old('inlineDefaultRadiosExample') != "Yes")
                                                @if (!$checkedtables)
                                                checked=""
                                                @endif
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
                                        value=""
                                    @elseif(old('inlineDefaultRadiosExample') == "Yes")
                                        value="{{ old('tablenr') }}"
                                    @else
                                        @if (!$checkedtables)
                                            disabled
                                            value="" 
                                        @else
                                             value="{{ $myevent->Tablenumber }}"
                                    @endif
                                        @endif>
                                        @if ($errors->has('tablenr'))
                                            <span class="invalid-feedback alerttablenr" role="alert">
                                            <strong>{{ $errors->first('tablenr') }}</strong>
                                            </span>
                                        @elseif($errors->has('inlineDefaultRadiosExample'))
                                            <span class="invalid-feedback alertinline" role="alert">
                                            <strong>{{ $errors->first('inlineDefaultRadiosExample') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-3 eventcreate">
                                        <label>Sēdvietas pie galda</label>
                                        <input style="width:62%;"type="number" name='seatsontablenr' id="seatsontable" class="form-control eventtable {{ $errors->has('seatsontablenr') ? ' is-invalid' : '' }}"
                                        @if(old('inlineDefaultRadiosExample') == "No")
                                        disabled
                                        value=""
                                    @elseif(old('inlineDefaultRadiosExample') == "Yes")
                                        value="{{ old('seatsontablenr') }}"
                                    @else
                                        @if (!$checkedtables)
                                            disabled
                                            value="" 
                                        @else
                                             value="{{ $myevent->Seatsontablenumber }}"
                                    @endif
                                        @endif>
                                        @if ($errors->has('seatsontablenr'))
                                            <span class="invalid-feedback alertseattable" role="alert">
                                            <strong>{{ $errors->first('seatsontablenr') }}</strong>
                                            </span>
                                        @elseif($errors->has('inlineDefaultRadiosExample'))
                                            <span class="invalid-feedback alertinline" role="alert">
                                            <strong></strong>
                                            </span>
                                        @endif
                                    </div>



                            <div class=" col-lg-12 eventcreate">
                                <label>Pasākuma anotācija</label>
                                <input type="text" name='anotation' class="form-control {{ $errors->has('anotation') ? ' is-invalid' : '' }}" id="eventanotation" 
                                @if(empty(old('anotation')))
                                value="{{ $myevent->Anotation }}"
                                @else
                                value="{{ old('anotation') }}"
                                @endif>
                                @if ($errors->has('anotation'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('anotation') }}</strong>
                                    </span>
                                @endif
                            </div>

                        
                            <div class=" col-lg-12 eventcreate">
                                <label>Pasākuma apraksts</label>
                                <textarea class="form-control" name='description' id="eventdescription" rows="3">
                                    @if(empty(old('description')))
                                    {{ $myevent->Description }}
                                    @else
                                    {{ old('description') }}
                                    @endif
                                </textarea>
                            </div>

                            <div class="custom-control custom-switch col-lg-12 eventcreate">
                                <div class="col-lg-2 eventcreate eventswitchs">
                              <input type="hidden" name="vipswitch" value="off" />
                              <input type="checkbox" class="custom-control-input" id="customSwitch1" name="vipswitch" 
                              @if(old('vipswitch') == "on") checked=""
                              @elseif(old('vipswitch') == "off")
                              @else
                                    @if ($myevent->VIP == 1)
                                        checked=""
                                    @endif
                                @endif>
                              <label class="custom-control-label" for="customSwitch1">VIP pasākums</label>
                            </div>

                            <div class="col-lg-3 eventcreate">
                                <input type="hidden" name="editableswitch" value="off" />
                                <input type="checkbox" class="custom-control-input" id="customSwitch2" name="editableswitch" 
                                @if(old('editableswitch') == "on") checked=""
                                @elseif(old('editableswitch') == "off")
                                @else
                                      @if ($myevent->Editable == 1)
                                          checked=""
                                      @endif
                                  @endif>
                                <label class="custom-control-label" for="customSwitch2">Rediģējams pasākums</label>
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
                                        @if(Storage::disk('public')->has($myevent->imgextension))
                                        <label class="custom-file-label {{ $errors->has('file') ? ' is-invalid' : '' }}" id="filename" for="inputGroupFile02">{{$myevent->imgextension}}</label>
                                        @else
                                        <label class="custom-file-label {{ $errors->has('file') ? ' is-invalid' : '' }}" id="filename" for="inputGroupFile02">Izvēlēties failu</label>
                                        @endif  
                                    </div>
                              </div>
                              <div class="col-lg-12 eventcreate buttons">
                                    <ul class="pdfcheckbox">
                               @for ($i = 0;$i < sizeof($pdf);$i++)
                                    
                                    <li class="pdfdownload" style="@if(sizeof($pdf) <= 2) max-width: 18%; @endif padding: 0;">
                                        <input type="hidden" name="pdfname{{$i}}" value="{{ $pdf[$i]->Name  }}" form="deletepdf">
                                        <input type="checkbox" class="pdfcb" name="pdfcheckbox{{$i}}" id="pdfcb{{$i}}" form="deletepdf"/>
                                        <label for="pdfcb{{$i}}">
                                            <img src="{{ asset('png-icon.jpg') }}" alt="png" width="40" height="40">
                                            <p class="small">{{ $pdf[$i]->Name }}</p>
                                        </label>
                                    </li>     

                                @endfor
                                    </ul>
                                
                              @if(sizeof($pdf) > 2)</div>@endif
                              <div class="col-lg-7 eventcreate" @if(sizeof($pdf) <= 2) style="margin-top: 3%;" @endif>
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
                                @if(sizeof($pdf) <= 2)</div>@endif
                            <div class="col-lg-12 eventcreate buttons">
                                        <div class="eventcreatebutton edit right"><button type="submit" class="btn btn-primary create right" name="action" value="create">
                                            @if ($myevent->Melnraksts == 0)
                                                Rediģēt
                                            @else
                                                Publicēt
                                            @endif</button></div>
                                        <div class="eventcreatebutton edit centered"><button type="submit" class="btn btn-primary save" name="action" value="save">Saglabāt</button></div>

                                        <div class="eventcreatebutton edit left"><button onclick="return confirm('Vai esi pārliecināts?')" type="submit" 
                                            class="btn btn-primary delete deleteevent" name="action" value="delete" form="deleteevent">Dzēst Pasākumu</button></div>
                                        @if(sizeof($pdf) !== 0)
                                        <div class="eventcreatebutton edit centered" @if(!Storage::disk('public')->has($myevent->imgextension)) style="width:100%;" @endif>
                                            <button onclick="return confirm('Vai esi pārliecināts?')" type="submit" 
                                            class="btn btn-primary delete deletepdf" name="action" value="delete" form="deletepdf">Dzēst pdf</button></div>
                                        @endif
                                        @if(Storage::disk('public')->has($myevent->imgextension))
                                        <div class="eventcreatebutton edit centered" @if(sizeof($pdf) === 0) style="width: 100%;" @endif>
                                            <button onclick="return confirm('Vai esi pārliecināts?')" type="submit" 
                                            class="btn btn-primary delete deletephoto" name="action" value="deletephoto" form="deletephoto">Dzēst foto</button></div>
                                        @endif
                            </div>
                        </fieldset>
                </form>
                @if(Storage::disk('public')->has($myevent->imgextension))
            <div class="col-lg-1 eventcreate deleteform photo">
                                    
                        <form id="deletephoto" action="{{ route('deletefile',['id' => $myevent->id,
                        'filename' => $myevent->imgextension]) }}" 
                        enctype="multipart/form-data" method="POST">

                            {{csrf_field()}}
                            
                        </form>
                     </div>
                @endif
                <div class="col-lg-1 eventcreate deleteform"> {{-- Dzēst funkcionalitāte --}}
                {!! Form::open(['method' => 'DELETE','route' => ['delete',$myevent->id],'id' => 'deleteevent']) !!}
                    
                {!! Form::close() !!}
            </div>
            <form id="deletepdf" action="{{ route('pdfdelete',['id' => $myevent->id]) }}" 
                    enctype="multipart/form-data" method="POST">

                        {{csrf_field()}}
                        
            </form>
        </div>
    </div>
</div>

@endsection