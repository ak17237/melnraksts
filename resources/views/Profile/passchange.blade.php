@extends('welcome')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
                <legend>{{Auth::user()->First_name}} password change</legend>
    <form class="form-horizontal" action="{{ route('profile.changepassword') }}" method="POST">
        {{csrf_field()}}

        <div class="form-group">
            <label for="exampleInputPassword1" class="col-lg-4 control-label">Your password</label>
            <div class="col-lg-12">
            <input type="password" class="form-control {{ $errors->has('oldpassword') ? ' is-invalid' : '' }}" id="exampleInputPassword1" name="oldpassword" placeholder="Password" >
            @if ($errors->has('oldpassword'))
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('oldpassword') }}</strong>
              </span>
             @endif
            </div>
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1" class="col-lg-4 control-label">New password</label>
            <div class="col-lg-12">
            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="exampleInputPassword1" name="password" placeholder="New password" >
            @if ($errors->has('password'))
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
             @endif
            </div>
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1" class="col-lg-6 control-label">Confirm new password</label>
            <div class="col-lg-12">
            <input type="password" class="form-control" id="exampleInputPassword1" name="password_confirmation" placeholder="Confirm new password" >
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Change</button><br>

            </form>
    </div>
</div>
</div>

@endsection