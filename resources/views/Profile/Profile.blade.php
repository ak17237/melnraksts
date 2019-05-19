@extends('welcome')
@section('content')
<div id="loadinger" class="fakeLoader"> {{-- Lapas loaders,pirms lapas attēli ielādējas --}}
    <script type="text/javascript"> 
    $('.content-page').addClass('loader'); // kamēr lapas sastāvs nav ielādējies nerādam to
        $.fakeLoader(); // izsauc funkciju kas pārvalde loaderi
        $(window).load(function() { // kad lapa ielādējas
            $('#loadinger').removeClass('fakeLoader'); // noņemt loadera klasi
            $('.content-page').removeClass('loader'); // noņemt klasi kas slēpa lapu
   
        });
   </script> 
<div class="container">
    <a href="javascript:window.location=document.referrer;" class="btn btn-primary back">Atpakaļ</a>
        <div class="row">
            <div class="col-lg-offset-3 col-lg-8 center profilediv">
              @if(Auth::check() && Auth::user()->hasRole('Admin'))
                    <div class="profiletab"><button id="profilename"><legend class="profilelegend">{{Auth::user()->First_name}} Profils</legend></button></div>
                    <div class="profiletab"><button style="float:right;" id="emailsend"><legend class="profilelegend">E-pastu sūtīšana</legend></button></div>
              @else
                    <legend>{{Auth::user()->First_name}} Profils</legend>
              @endif
                    <br><br><br>
                    <div class="profileinfo">
                    @if(session()->has('message'))
                        <div class="alert alert-dismissible alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <p>{{ session()->get('message') }}</p>
                        </div>
                        <br>
                        @endif

                    <form id="addavatar" class="left" method="POST" action="{{ route('changeavatar') }}" enctype="multipart/form-data">
                      {{csrf_field()}}
                      @if(Storage::disk('avatar')->has(Auth::user()->Avatar))
                        <div class="imageContainer profileavatar">
                          <img class="outerImage" width="146" heigth="146" src="/profile-avatar/{{Auth::user()->Avatar}}" alt="Profile-avatar">
                          <div class="middle">
                            <div class="avatarchange">
                                <label for="avatar">
                              <img src="/exchange.png" alt="EyeIcon" width="24" height="24">
                                </label>
                            </div>
                          </div>
                        </div>          
                        
                      @else
                      <div class="imageContainer profileavatar">
                          <img class="outerImage" src="/Empty-Avatar.png" alt="Empty-Avatar">
                          <div class="middle">
                            <div class="avatarchange">
                              <label for="avatar">
                              <img src="/exchange.png" alt="EyeIcon" width="24" height="24">
                            </label>
                            </div>
                          </div>
                      </div>
                      
                      @endif
                      <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg,jpg,gif">
                  </form>

        <form class="form-horizontal left col-lg-8" action="{{ route('profile.changename') }}" method="POST">
            <hr class="upperhr">
            {{csrf_field()}}

            
        <div class="form-group">
          
          <div class="col-lg-12">
              <label for="Inputname" class="label-control col-lg-12 control-label">Vārds</label>
              <p class="left fnametext profiletext">{{$First_name}}</p>
              <input type="text" class="form-control left fname {{ $errors->has('fname') ? ' is-invalid' : '' }}"
              name="fname" placeholder="Ievadiet vārdu" 
              @if(!empty(old('fname')))
              value="{{ old('fname') }}"
              @else
              value="{{ $First_name }}"
              @endif 
              style="width:50%">
             <button type="button" class="changename btn btn-primary profile left ml-7-p">Izmainīt</button>
            <button type="submit" class="savename btn btn-primary profile left ml-7-p" name="action" value="fname">Saglabāt</button><br>
            <button type="button" class="cancelname btn btn-primary profile left" style="margin-left: 4%;">Atcelt</button>
            @if ($errors->has('fname'))
              <span class="invalid-feedback left" id="fname" role="alert">
              <strong>{{ $errors->first('fname') }}</strong>
              </span>
             @endif
          </div>
        </div><hr class="middlehr">
        </form>
        
        <form class="form-horizontal left col-lg-8 mb-4-p" action="{{ route('profile.changesurname') }}" method="POST">
            {{csrf_field()}}

            
        <div class="form-group">
          
          <div class="col-lg-12">
              <label for="Inputname" class="label-control col-lg-12 control-label">Uzvārds</label>
              <p class="left lnametext profiletext">{{$Last_name}}</p>
              <input type="text" class="form-control left lname {{ $errors->has('lname') ? ' is-invalid' : '' }}"
              name="lname" placeholder="Ievadiet uzvārdu" 
              @if(!empty(old('lname')))
              value="{{ old('lname') }}"
              @else
              value="{{ $Last_name }}" 
              @endif
              style="width:50%">
            <button type="button" class="changesurname btn btn-primary profile left ml-7-p">Izmainīt</button>
            <button type="submit" class="savesurname btn btn-primary profile left ml-7-p" name="action" value="lname">Saglabāt</button><br>
            <button type="button" class="cancelsurname btn btn-primary profile left" style="margin-left: 4%;">Atcelt</button>
            @if ($errors->has('lname'))
              <span class="invalid-feedback left" id="lname" role="alert">
                <strong>{{ $errors->first('lname') }}</strong>
              </span>
            @endif
          </div>
        </div><hr class="lowerhr">
        </form>
    
            <form class="form-horizontal left col-lg-12 mb-4-p" action="{{ route('profile.changeemail') }}" method="POST"><hr style="margin: 15px 0px 10px 0;">
                    {{csrf_field()}}
                    

                <div class="form-group" style="margin-bottom: 0px;">
                  <label for="Inputname" class="col-lg-4 control-label">E-pasts</label>
                  <div class="col-lg-12">
                  <p class="left emailtext profiletext">{{$Email}}</p>
                  <input type="email" class="left form-control email {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                  name="email" placeholder="Ievadiet e-pastu" 
                  @if(!empty(old('email')))
                  value="{{ old('email') }}"
                  @elseif(session()->has('oldemail'))
                  value="{{ session()->get('oldemail') }}"
                  @else
                  value="{{ $Email }}" 
                  @endif
                  style="width:50%;">
                  <button type="button" class="changeemail btn btn-primary profile left ml-7-p">Izmainīt</button>
                <button type="submit" class="saveemail btn btn-primary profile left" style="margin-left: 7%;" name="action" value="email">Saglabāt</button>
                <button type="button" class="cancelemail btn btn-primary profile left" style="margin-left: 4%;">Atcelt</button>
                  </div>
                </div>
                @if ($errors->has('email'))
                  <span class="invalid-feedback left display" id="email" role="alert">
                  <strong class="ml-2-p">{{ $errors->first('email') }}</strong>
                  </span>
                @endif
                <br><hr style="margin: 15px 0px 10px 0;">
                </form>

                <form class="form-horizontal left col-lg-12 mb-4-p" action="{{ route('profile.changepassword') }}" method="POST">
                    {{csrf_field()}}
                    

                <div class="form-group passdiv" style="margin-bottom: 0px;">
                  <label for="Inputname" class="col-lg-4 control-label passtitle">Parole</label>
                  <div class="col-lg-12 passinputdiv" style="text-align:-webkit-center;">
                  <p class="left passtext" style="width:50%;">********</p>
                  <input type="password" class="form-control changepassinput pass {{ $errors->has('oldpassword') ? ' is-invalid' : '' }}" 
                  name="oldpassword" placeholder="Parole" >
                  @if ($errors->has('oldpassword'))
                    <span class="invalid-feedback" id="oldpass" role="alert">
                    <strong>{{ $errors->first('oldpassword') }}</strong>
                    </span>
                   @endif
                   <input type="password" class="form-control changepassinput pass {{ $errors->has('password') ? ' is-invalid' : '' }}" 
                  name="password" placeholder="Jauna parole" >
                    @if ($errors->has('password'))
                      <span class="invalid-feedback" id="pass" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                      </span>

                    @endif
                  <input type="password" class="form-control pass changepassinput" style="margin-bottom: 3%;" 
                  name="password_confirmation" placeholder="Apstiprināt paroli" >
                  <button type="button" class="changepass btn btn-primary profile left ml-7-p">Izmainīt</button>
                  <button type="submit" class="savepass btn btn-primary profile right" style="margin-left: 7%;margin-right: 25%;" name="action" value="pass">Saglabāt</button>
                  <i class="far fa-question-circle right" id="resetpasstooltip" style="padding-left: 1%;padding-top: 1%;"></i>
                                <div class="questiontooltip"></div>
                  <button type="submit" class="resetpass btn btn-primary profile right" style="margin-left: 10%;" name="action" value="reset" form="resetpassword">Atjaunot</button>
                  <button type="button" class="cancelpass btn btn-primary profile right">Atcelt</button>
                  
                  </div>
                </div>
                </form>
                {!! Form::open(['method' => 'POST','route' => ['reset'],'id' => 'resetpassword']) !!}
                    
                {!! Form::close() !!}
              </div>
              <div class="emailinfo">
                  @if(session()->has('emailmessage'))
                  <div class="alert alert-dismissible alert-success">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <p>{{ session()->get('emailmessage') }}</p>
                  </div>
                  <br>
                  @endif
                  {!! Form::open(['method' => 'POST','route' => ['sendemail']]) !!}
                  <div class="custom-control custom-switch" style="text-align: -webkit-center;">
                      <input type="hidden" name="vipswitch" value="off" />
                      <input type="checkbox" class="custom-control-input" id="customSwitch1" name="transportcb"
                      @if(old('transportcb') == "on") 
                      checked="" 
                      @endif>
                      <label class="custom-control-label" for="customSwitch1">Transporta e-pasts</label>
                      <i class="far fa-question-circle" id="transportemail"></i>
                                <div class="questiontooltip"></div>
                    </div>
                    
                 
                  <div class="col-lg-12 eventcreate">
                    <label for="reciever">Saņēmēji</label>
                      <select multiple class="reciever-js-search form-control reciever {{ $errors->has('reciever') ? ' is-invalid' : '' }}" name="reciever[]" id="reciever">
                        @for ($i = 0;$i < sizeof($user);$i++)
                            <option value="{{ $user[$i]->email }}"
                              @if(old('reciever') != null) {{-- Lai saglabātu old vērtību pārbaudam vai tā ir --}}
                              @for($j = 0;$j < sizeof(old('reciever'));$j++) {{-- cikls lai pāriet caur visām old vērtībām jo ir multiple select --}}
                               @if($user[$i]->email == old('reciever.' . $j)) selected @endif {{-- ja kāda no old vērtībām ir tāda paša kā ši option vērtība tad atzīmēt select --}}
                               @endfor @endif>
                               {{ $user[$i]->email }}</option>
                        @endfor
                      </select>
                      <select class="transport-js-search form-control transport {{ $errors->has('transport') ? ' is-invalid' : '' }}" name="transport" id="selectemailtransport">
                          @for ($i = 0;$i < sizeof($transport);$i++)
                              <option @if($eventid[$i] == old('transport')) selected @endif value="{{ $eventid[$i] }}">{{ $transport[$i] }}</option>
                          @endfor
                      </select>
                      @if ($errors->has('reciever'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('reciever') }}</strong>
                        </span>
                      @endif
                      @if ($errors->has('transport'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('transport') }}</strong>
                        </span>
                      @endif
                  </div>

                  <div class="col-lg-12 eventcreate">
                      <label>Virsraksts</label>
                      <input type="text" name='emailtitle' class="form-control {{ $errors->has('emailtitle') ? ' is-invalid' : '' }}" value="{{ old('emailtitle') }}">
                      @if ($errors->has('emailtitle'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('emailtitle') }}</strong>
                        </span>
                      @endif
                  </div>

                  <div class=" col-lg-12 eventcreate">
                      <label>Sūtījuma ziņa</label>
                      <textarea class="form-control {{ $errors->has('emailtext') ? ' is-invalid' : '' }}" name='emailtext' id="eventdescription" rows="10">{{ old('emailtext') }}</textarea>
                      @if ($errors->has('emailtext'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('emailtext') }}</strong>
                        </span>
                      @endif
                  </div>
                  <div class="col-lg-12 eventcreate">
                  <div class="radiocontainer eventcreate">
                    <label class="seats" style="width:100%">Linka poga</label>
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

                    <div class="col-lg-4 eventcreate" style="margin-left: 3.9%">
                      <label>Pogas uzraksts</label>
                      <input type="text" name='buttontitle' id="eventtable" class="form-control eventtable {{ $errors->has('buttontitle') ? ' is-invalid' : '' }}" value="{{ old('buttontitle') }}">
                      @if ($errors->has('buttontitle'))
                        <span class="invalid-feedback alertbuttontitle" role="alert">
                        <strong>{{ $errors->first('buttontitle') }}</strong>
                        </span>
                      @endif
                    </div>
                    <div class="col-lg-4 eventcreate">
                      <label>Pogas links</label>
                      <input type="text" name='buttonlink' id="seatsontable" class="form-control eventtable {{ $errors->has('buttonlink') ? ' is-invalid' : '' }}" value="{{ old('buttonlink') }}">
                      @if ($errors->has('buttonlink'))
                        <span class="invalid-feedback alertbuttonlink" role="alert">
                        <strong>{{ $errors->first('buttonlink') }}</strong>
                        </span>
                      @endif
                    </div>
                  </div>
                    <div class="col-lg-12 eventcreate">
                      <button type="submit" class="btn btn-primary profile right send"name="action" value="send">Sūtīt</button>
                      <button type="submit" class="btn btn-primary profile right mr-12-p preview" name="action" value="preview">Skatīt</button>
                    </div>
                    {!! Form::close() !!}
                </div>
              </div>
              <br><br><br>
        </div>
    </div>
</div>
</div>
@endsection