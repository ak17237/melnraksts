@extends('welcome')
@section('content')

<div class="container">
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6">
                    <div><legend class="col-lg-6 control-legend">{{Auth::user()->First_name}} Profile</legend> <a href="/" id='backlink'>Back</a></div><br>
        <form class="form-horizontal" action="{{ route('profile.changename') }}" method="POST">
            {{csrf_field()}}
            @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
                </div>
            @endif
            
        <div class="form-group">
          
          <div class="col-lg-12">
              <label for="Inputname" class="label-control col-lg-4 control-label">First Name</label>
          <input type="text" class="form-control {{ $errors->has('fname') ? ' is-invalid' : '' }} {{ (session()->has('fname')) ? ' is-valid' : '' }}" name="fname" placeholder="Enter first name" value="{{ $First_name }}">
          @if ($errors->has('fname'))
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('fname') }}</strong>
              </span>
             @endif
             @if(session()->has('fname'))
             <span class="valid-feedback" role="alert">
              <strong>{{ session()->get('fname') }}</strong>
             </span>
            @endif

          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Change</button><br>
        </form>
    
        <form class="form-horizontal" action="{{ route('profile.changesurname') }}" method="POST">
                {{csrf_field()}}
                

            <div class="form-group">
              <label for="Inputname" class="col-lg-4 control-label">Last Name</label>
              <div class="col-lg-12">
              <input type="text" class="form-control {{ $errors->has('lname') ? ' is-invalid' : '' }} {{ (session()->has('lname')) ? ' is-valid' : '' }}" name="lname" placeholder="Enter last name" value="{{ $Last_name }}">
              @if ($errors->has('lname'))
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('lname') }}</strong>
              </span>
             @endif
             @if(session()->has('lname'))
             <span class="valid-feedback" role="alert">
              <strong>{{ session()->get('lname') }}</strong>
             </span>
            @endif
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Change</button><br>
            </form>
    
            <form class="form-horizontal" action="{{ route('profile.changeemail') }}" method="POST">
                    {{csrf_field()}}
                    

                <div class="form-group">
                  <label for="Inputname" class="col-lg-4 control-label">Email</label>
                  <div class="col-lg-12">
                  <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }} {{ (session()->has('email')) ? ' is-valid' : '' }}" name="email" placeholder="Enter email" value="{{ $Email }}">
                  @if ($errors->has('email'))
          <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
             @endif
             @if(session()->has('email'))
             <span class="valid-feedback" role="alert">
              <strong>{{ session()->get('email') }}</strong>
             </span>
            @endif
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Change</button><br>
                </form>

                <form class="form-horizontal" action="{{ route('profile.changepass') }}" method="GET">
                        {{csrf_field()}}
                        
    
                    <div class="form-group">
                      <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary btn-block" name="password">Change password</button><br>
                      </div>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection