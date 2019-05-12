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
       <div class="over-content"></div>
       <div id='editAlert' class="alert alert-dismissible alert-secondary">
            <strong style="font-weight: 600;">Rediģēšanas režīms ieslēgts!</strong>Lai izslēgtu nospiediet krustiņu.
            <img class="close-icon closeEdit" src="/svg/close.svg" alt="Circle-Plus" width="24" height="24">
            <button type="submit" class="submitGallery" form="deletegallery"><img class="trash-icon" src="/svg/trash.svg" alt="Circle-Plus" width="24" height="24"></button>
        </div>
    <div class="content" id="gallery-content">
            <a href="javascript:window.location=document.referrer;" class="btn btn-primary back left ml-7-p">Atpakaļ</a>
            @if(sizeof($gallery) > 0)
            <button type="button" id="editGallery" class="btn btn-primary back right mr-7-p">Rediģēt</button>
            @endif
            <br><br>
        <div class="title m-b-md">
            Galerija
        </div>
        @include('errors')
        <div class="alert alert-dismissible alert-warning" style="display: none;padding-top: 20px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p class="mb-0 text-warn"></p><br>
        </div>
        @if(session()->has('message'))
                <div class="alert alert-dismissible alert-success" style="margin-top: 79px;margin-bottom: 20px;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p class="mb-0">{{ session()->get('message') }}</p>
                </div>
            @endif
            @if(Auth::check() && Auth::user()->hasRole('User') && sizeof($gallery) == 0)
            <h3>Galerija ir tukša</h3>
            @else
        <div class="gallery-content">
            <div class="con-ov">
                <br>
                @for($i = 0;$i < sizeof($gallery);$i++)
            <div class="gallery-photo imgcheckbox">
                    <input type="hidden" name="imgname{{$i}}" value="{{ $gallery[$i]->Name  }}" form="deletegallery">
                    <input type="checkbox" class="imgcb" name="imgcheckbox{{$i}}" id="imgcb{{$i}}" form="deletegallery"/>

                    <label for="imgcb{{$i}}" class="innerImage">
                    <img src="/event-gallery/{{$gallery[$i]->Name}}" alt="{{$gallery[$i]->Name}}" width="300" height="168.75">
                    </label>
                    <div class="imageContainer">
                    <img class="outerImage" id="galerryImg{{$i}}" src="/event-gallery/{{$gallery[$i]->Name}}" alt="{{$gallery[$i]->Name}}" width="300" height="168.75">
                        <div class="middle">
                            <div class="text">
                                <img src="/svg/eye.svg" alt="EyeIcon" width="24" height="24">
                            </div>
                        </div>
                    </div>          
                
            </div>
            @endfor

            <!-- The Modal -->
            <div id="myModal" class="modal">

                <!-- The Close Button -->
                <span class="closeimg">&times;</span>

                <!-- Modal Content (The Image) -->
                <img class="modal-content" id="img01">

                <!-- Modal Caption (Image Text) -->
                <div id="caption"></div>
            </div>

            @if(Auth::check() && Auth::user()->hasRole('Admin'))
            <div class="add-gallery">
                <form id="addphotosgallery"  method="POST" action="{{ route('uploadgallery',$id) }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <label for="gallery">
                    <img class="plus-icon" src="/svg/plus.svg" alt="Circle-Plus" width="48" height="48">
                    </label>
                    <input multiple type="file" id="gallery" name="gallery[]" accept="image/png, image/jpeg,jpg,gif">
                </form>
            </div>
            <form id="deletegallery" action="{{ route('deletegallery',$id) }}" 
                    enctype="multipart/form-data" method="POST">

                        {{csrf_field()}}
                        
                    </form>
            @endif
        </div>
        @endif
        </div>
        <br>
    </div>
</div>
@endsection