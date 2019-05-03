@extends('welcome')
@section('content')
<div class="container">
    <a href="/" class="btn btn-primary back">Atpakaļ</a>
    @if(session()->has('message'))
      <br>
      <div class="alert alert-dismissible alert-success" style="margin-top: 20px;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <p>{{ session()->get('message') }}</p>
      </div>
    @endif
  <div class="content">
    <div class="title m-b-md">
     
        Melnraksti
    </div>
    <div class="contain" style="width: 70%"> 
      <table class="eventtable">
        @if ($data->count() == 0)
          <h3><i>Nav saglabātu pasākumu.</i></h3>
        @else
          <thead>
            <tr>
              <th scope="col" class="content">Datums</th>
              <th class="space" scope="col">Pasākums</th>
              <th class="space" scope="col"></th>
            </tr>
          </thead>
          
          @foreach ($data as $d){{-- līdzīgi kā slierī izvada pasākumus (home.blade.php) --}}
            <tbody>
              <tr>
                <td class="top">
                  <div class="eventdate">
                      <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($d->Datefrom) }}</span></div>
                      <div class="eventmonth block h-center v-center"><span class="pagmonth{{ $counter }}">Mēnesis</span></div>
                    </div>
                </td>
                <td class="top space eventinfo">
                  <h5>{{ $d->Title }}</h5>
                  <p>Kad: {{ geteventdate($d->Datefrom) }}</p><span id='eventdate{{ $counter++ }}'style="display:none">{{ $d->Datefrom }}</span>
                  <p>Kur: {{ $d->Address }}</p>
                  <i>{{ $d->Anotation }}</i>
                </td>
                <td class="space"><a href="{{ route('showedit',$d->id) }}" class="button">Rediģēt</a></td>
              </tr>
            </tbody>
          @endforeach
        @endif
      </table>
    </div> {{-- paginācijas linki --}}
        <span style="display:none" id="counter">{{ $counter }}</span>
        @if ($data->count() > 0)
          <ul class="slider-months">
            @for ($i = 0; $i < count($pagenumber); $i++)
              <li class="slider-months_item">
                <a href="{{ route('showsavedevents',$i + 1)}}"  class="button">{{ $i + 1 }}</a>
              </li>
            @endfor
          </ul>
          <br>
        @endif
  </div>
</div>
@endsection