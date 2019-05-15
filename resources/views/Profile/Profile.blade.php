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
            <div class="col-lg-offset-3 col-lg-8 center" style="max-width: 100%;flex: 0 0 80.666667%;">
                    <div><legend class="col-lg-6 control-legend">{{Auth::user()->First_name}} Profils</legend></div><br><br><br>

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
                        <div class="imageContainer" style="width: 166px;height: 166px">
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
                      <div class="imageContainer" style="width: 146px">
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
            <hr style="margin: 0px 0px 3px 0;width: 100%;">
            {{csrf_field()}}

            
        <div class="form-group">
          
          <div class="col-lg-12">
              <label for="Inputname" class="label-control col-lg-12 control-label">Vārds</label>
              <p class="left fnametext" style="margin:0;width:50%;font-size: 1rem;font-weight: 400;padding-top: 7.5px;padding-left: 13px;">{{$First_name}}</p>
              <input type="text" class="form-control left fname {{ $errors->has('fname') ? ' is-invalid' : '' }}"
              name="fname" placeholder="Ievadiet vārdu" value="{{ $First_name }}" style="width:50%">
             <button type="button" class="changename btn btn-primary profile left ml-7-p">Izmainīt</button>
            <button type="submit" class="savename btn btn-primary profile left ml-7-p" name="action" value="fname">Saglabāt</button><br>
            <button type="button" class="cancelname btn btn-primary profile left" style="margin-left: 4%;" style="margin-left: 4%;">Atcelt</button>
            @if ($errors->has('fname'))
              <span class="invalid-feedback left" id="fname" role="alert">
              <strong>{{ $errors->first('fname') }}</strong>
              </span>
             @endif
          </div>
        </div><hr style="margin: 10px 0px 7px 0;width: 100%;">
        </form>
        
        <form class="form-horizontal left col-lg-8 mb-4-p" action="{{ route('profile.changesurname') }}" method="POST">
            {{csrf_field()}}

            
        <div class="form-group">
          
          <div class="col-lg-12">
              <label for="Inputname" class="label-control col-lg-12 control-label">Uzvārds</label>
              <p class="left lnametext" style="margin:0;width:50%;font-size: 1rem;font-weight: 400;padding-top: 7.5px;padding-left: 13px;">{{$Last_name}}</p>
              <input type="text" class="form-control left lname {{ $errors->has('lname') ? ' is-invalid' : '' }}"
              name="lname" placeholder="Ievadiet uzvārdu" value="{{ $Last_name }}" style="width:50%">
            <button type="button" class="changesurname btn btn-primary profile left ml-7-p">Izmainīt</button>
            <button type="submit" class="savesurname btn btn-primary profile left ml-7-p" name="action" value="lname">Saglabāt</button><br>
            <button type="button" class="cancelsurname btn btn-primary profile left" style="margin-left: 4%;">Atcelt</button>
            @if ($errors->has('lname'))
              <span class="invalid-feedback left" id="lname" role="alert">
                <strong>{{ $errors->first('lname') }}</strong>
              </span>
            @endif
          </div>
        </div><hr style="margin: 15px 0px 10px 0;width: 100%;">
        </form>
    
            <form class="form-horizontal left col-lg-12 mb-4-p" action="{{ route('profile.changeemail') }}" method="POST"><hr style="margin: 15px 0px 10px 0;">
                    {{csrf_field()}}
                    

                <div class="form-group" style="margin-bottom: 0px;">
                  <label for="Inputname" class="col-lg-4 control-label">E-pasts</label>
                  <div class="col-lg-12">
                  <p class="left emailtext" style="margin:0;width:50%;font-size: 1rem;font-weight: 400;padding-top: 7.5px;padding-left: 13px;">{{$Email}}</p>
                  <input type="email" class="left form-control email {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                  name="email" placeholder="Enter email" value="{{ $Email }}" style="width:50%;">
                  <button type="button" class="changeemail btn btn-primary profile left ml-7-p">Izmainīt</button>
                <button type="submit" class="saveemail btn btn-primary profile left" style="margin-left: 7%;" name="action" value="email">Saglabāt</button>
                <button type="button" class="cancelemail btn btn-primary profile left" style="margin-left: 4%;">Atcelt</button>
                  </div>
                </div>
                @if ($errors->has('email'))
                  <span class="invalid-feedback left" id="email" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
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
                  <input style="width: 50%;margin-top: 3%;margin-left: 25%;margin-right: 25%;" type="password" class="form-control pass {{ $errors->has('oldpassword') ? ' is-invalid' : '' }}" 
                  name="oldpassword" placeholder="Parole" >
                  @if ($errors->has('oldpassword'))
                    <span class="invalid-feedback" id="oldpass" role="alert">
                    <strong>{{ $errors->first('oldpassword') }}</strong>
                    </span>
                   @endif
                   <input style="width: 50%;margin-top: 3%;margin-left: 25%;margin-right: 25%;" type="password" class="form-control pass {{ $errors->has('password') ? ' is-invalid' : '' }}" 
                  name="password" placeholder="Jauna parole" >
                    @if ($errors->has('password'))
                      <span class="invalid-feedback" id="pass" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                      </span>

                    @endif
                  <input style="width: 50%;margin-top: 3%;margin-bottom: 3%;margin-left: 25%;margin-right: 25%;" type="password" class="form-control pass" 
                  name="password_confirmation" placeholder="Apstiprināt paroli" >
                  <button type="button" class="changepass btn btn-primary profile left ml-7-p">Izmainīt</button>
                  <button type="submit" class="savepass btn btn-primary profile right" style="margin-left: 7%;margin-right: 25%;" name="action" value="pass">Saglabāt</button>
                  <i class="far fa-question-circle right" id="resetpasstooltip" style="padding-left: 1%;padding-top: 1%;"></i>
                                <div class="questiontooltip"></div>
                  <button type="submit" class="resetpass btn btn-primary profile right" style="margin-left: 10%;" name="action" value="reset" form="reserpassword">Atjaunot</button>
                  <button type="button" class="cancelpass btn btn-primary profile right">Atcelt</button>
                  
                  </div>
                </div>
                </form>
                {!! Form::open(['method' => 'POST','route' => ['reset'],'id' => 'reserpassword']) !!}
                    
                {!! Form::close() !!}
        </div>
    </div>
</div>
</div>
@endsection