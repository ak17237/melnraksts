@extends('welcome')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">

        <form class="form-horizontal" action="{{ route('login') }}" method="POST">
        {{csrf_field()}}
      
  <fieldset>
    <legend>Log in</legend>

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

    <button type="submit" class="btn btn-primary btn-block">Login</button><br>
  </fieldset>
</form>
<a href="/" class="btn btn-primary btn-block">Back</a>
        </div>
    </div>
</div>
@endsection