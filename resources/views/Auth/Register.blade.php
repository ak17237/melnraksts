@extends('welcome')
@section('content')

<div class="container">
    <div class="row">
        <a href="javascript:window.location=document.referrer;" class="btn btn-primary back">Atpakaļ</a>
        <div class="col-lg-offset-3 col-lg-6 center">

        <form class="form-horizontal" action="{{ route('register') }}" method="POST">
        {{csrf_field()}}
  <fieldset>
    <legend class="smalltitle m-b-md">Reģistrēties</legend>
    <div class="form-group">
      <label for="Inputname" class="col-lg-4 control-label">Vārds</label>
      <div class="col-lg-12">
      <input type="text" class="form-control {{ $errors->has('fname') ? ' is-invalid' : '' }}" name="fname" placeholder="Ievadiet vārdu" value="{{ old('fname') }}">
      @if ($errors->has('fname'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('fname') }}</strong>
        </span>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="Inputname" class="col-lg-4 control-label">Uzvārds</label>
      <div class="col-lg-12">
      <input type="text" class="form-control {{ $errors->has('lname') ? ' is-invalid' : '' }}" name="lname" placeholder="Ievadiet uzvārdu" value="{{ old('lname') }}">
      @if ($errors->has('lname'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('lname') }}</strong>
        </span>
       @endif
    </div>
    </div>

    <div class="form-group">
      <label for="exampleInputEmail1" class="col-lg-4 control-label">E-pasts</label>
      <div class="col-lg-12">
      <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Ievadiet e-pastu" value="{{ old('email') }}">
      @if ($errors->has('email'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @else <small id="emailHelp" class="form-text text-muted">Mēs nekad nepublicēsim jūsu e-pasta ziņojumus nevienam citam.</small>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="exampleInputPassword1" class="col-lg-4 control-label">Parole</label>
      <div class="col-lg-12">
      <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="exampleInputPassword1" name="password" placeholder="Parole" >
      @if ($errors->has('password'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="exampleInputPassword1" class="col-lg-4 control-label">Apstiprināt paroli</label>
      <div class="col-lg-12">
      <input type="password" class="form-control" id="exampleInputPassword1"  name="password_confirmation" placeholder="Parole">
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block login">Reģistrēties</button><br>
  </fieldset>
</form>
        </div>
    </div>
</div>

@endsection