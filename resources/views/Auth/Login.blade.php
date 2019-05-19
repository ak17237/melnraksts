@extends('welcome')
@section('content')

<div class="container">
    <div class="row">
        <a href="javascript:window.location=document.referrer;" class="btn btn-primary back">Atpakaļ</a>
        <div class="col-lg-offset-3 col-lg-6 center">

        <form class="form-horizontal" action="{{ route('login') }}" method="POST">
        {{csrf_field()}}
        
      
  <fieldset>
    <legend class="smalltitle m-b-md">Ielogoties</legend>
    <div class="form-group">
      <label for="exampleInputEmail1" class="col-lg-4 control-label">E-pasts</label>
      <div class="col-lg-12">
      <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email" 
      @if(!empty(old('email')))
      value="{{ old('email') }}"
      @elseif(!empty(request()->cookie('email')))
      value="{{ request()->cookie('email') }}"
      @endif>
      @if ($errors->has('email'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
            
        </span>
        @else <small id="emailHelp" class="form-text text-muted">
            Mēs nekad nepublicēsim jūsu e-pasta ziņojumus nevienam citam.</small>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="exampleInputPassword1" class="col-lg-4 control-label">Parole</label>
      <div class="col-lg-12">
      <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="exampleInputPassword1" name="password" placeholder="Password" 
      @if(!empty(old('password')))
      value=""
      @elseif(!empty(request()->cookie('password')))
      value="{{ request()->cookie('password') }}"
      @endif>
      @if ($errors->has('password'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
       @endif
      </div>
    </div>
    
    <div class="form-group">
        <div class="custom-control custom-checkbox col-lg-5 left rememberusers">
          <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="custom-control-label" for="customCheck1">Atceries mani</label>
        </div>
        <div class="custom-control custom-checkbox col-lg-7 left">
            <input type="checkbox" class="custom-control-input" id="customCheck2" name="resetuser" {{ old('resetuser') ? 'checked' : '' }}>
            <label class="custom-control-label" for="customCheck2">Ielogoties izmantojot Latvenergo datus</label>
        </div>
        <br><br>
    </div>
    

    <button type="submit" class="btn btn-primary btn-block login">Ielogoties</button><br>
  </fieldset>
</form>
        </div>
    </div>
</div>
@endsection