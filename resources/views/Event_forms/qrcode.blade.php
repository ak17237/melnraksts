@extends('welcome')
@section('PageTitle',geteventbyid($id)->Title . ' biļešu skanēšana')
@section('content')
<div>
</div>
    <div class="qrcode">
        @if(session()->has('message'))
            <div class="alert alert-dismissible alert-success qr-success" style="margin-top: 79px;margin-bottom: 20px;">
                <button type="button" class="close qr-close" data-dismiss="alert">&times;</button>
                <p class="mb-0" id="qrcode-success">{{ session()->get('message') }}</p>
            </div>
        @endif
        
        @if($errors->has('qrcode'))
            <div class="alert alert-dismissible alert-danger qr-warning" style="padding-top: 20px;display:block">
                <button type="button" class="close qr-close" data-dismiss="alert">&times;</button>
                <p class="mb-0" id="qrcode-error">{{ $errors->first('qrcode') }}</p><br>
            </div>
        @endif
        
        <h2 class="qrcode-text">Lai noskanētu uzspiediet šeit</h2>
        <img class="qrcode-arrow" src="/svg/download-arrow.svg" alt="QrcodeIcon" width="100" height="100">
        <label for="qrcode" class=qrcode-text-btn>
            <img src="/svg/qr-code.svg" alt="QrcodeIcon" width="200" height="200">
        </label>
        <input type=file accept="image/*" id="qrcode" capture=environment>
    <form action="{{ route('qrcode',$id) }}" enctype="multipart/form-data" method="POST" id="scanqrcode">
        {{ csrf_field() }}
        <input style="top:80%"type="text" name="qrcode" class="qrcoderesult">
    </form>
    </div>
@endsection