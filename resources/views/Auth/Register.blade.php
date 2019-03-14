@extends('welcome')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">

        <form class="form-horizontal" action="{{ route('register') }}" method="POST">
        {{csrf_field()}}
  <fieldset>
    <legend>Registration</legend>
    <div class="form-group">
      <label for="Inputname" class="col-lg-4 control-label">First Name</label>
      <div class="col-lg-12">
      <input type="text" class="form-control {{ $errors->has('fname') ? ' is-invalid' : '' }}" name="fname" placeholder="Enter first name" value="{{ old('fname') }}">
      @if ($errors->has('fname'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('fname') }}</strong>
        </span>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="Inputname" class="col-lg-4 control-label">Last Name</label>
      <div class="col-lg-12">
      <input type="text" class="form-control {{ $errors->has('lname') ? ' is-invalid' : '' }}" name="lname" placeholder="Enter last name" value="{{ old('lname') }}">
      @if ($errors->has('lname'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('lname') }}</strong>
        </span>
       @endif
    </div>
    </div>

    <div class="form-group">
      <label for="exampleInputEmail1" class="col-lg-4 control-label">Email address</label>
      <div class="col-lg-12">
      <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{ old('email') }}">
      @if ($errors->has('email'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @else <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="exampleInputPassword1" class="col-lg-4 control-label">Password</label>
      <div class="col-lg-12">
      <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="exampleInputPassword1" name="password" placeholder="Password" >
      @if ($errors->has('password'))
    <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
       @endif
      </div>
    </div>

    <div class="form-group">
      <label for="exampleInputPassword1" class="col-lg-4 control-label">Confirm Password</label>
      <div class="col-lg-12">
      <input type="password" class="form-control" id="exampleInputPassword1"  name="password_confirmation" placeholder="Password">
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Register</button><br>
  </fieldset>
</form>
<a href="/" class="btn btn-primary btn-block">Back</a>
        </div>
    </div>
</div>

@endsection