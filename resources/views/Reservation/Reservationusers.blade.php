@extends('welcome')
@section('content')
<div class="container">
    <a href="javascript:window.location=document.referrer;" class="btn btn-primary back">Atpakaļ</a>
    <br>
    @if(session()->has('message'))
    <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>{{ session()->get('message') }}</p>
    </div>
    @endif
<div class="content">
    @if ($reservations->count() > 0)
    <div class="form-group myresrvsearch">
        <label class="col-form-label" for="inputDefault">Meklēt rezervāciju</label>
        <input type="text" class="form-control" placeholder="Meklēt..." id="myresrvsearchinput">
    </div>
        <div class="reservsearchcbdiv">
        <div class="custom-control custom-checkbox myreservsearchcb">
          <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
          <label class="custom-control-label" for="customCheck1">Pēc nosaukuma</label>
        </div>
        <div class="custom-control custom-checkbox myreservsearchcb">
          <input type="checkbox" class="custom-control-input" id="customCheck2" checked="">
          <label class="custom-control-label" for="customCheck2">Pēc datuma</label>
        </div>
        <div class="custom-control custom-checkbox myreservsearchcb">
          <input type="checkbox" class="custom-control-input" id="customCheck3" checked="">
          <label class="custom-control-label" for="customCheck3">Pēc adreses</label>
        </div>
        <div class="custom-control custom-checkbox myreservsearchcb">
            <input type="checkbox" class="custom-control-input" id="customCheck4" checked="">
            <label class="custom-control-label" for="customCheck4">Pēc biļešu skaita</label>
        </div>
      </div>
      @endif
    <div class="smalltitle m-b-md">
     
        Manas rezervācijas
    </div>
    <div class="contain" style="width: 70%"> 
        <table class="eventtable">
          @if ($reservations->count() == 0)
            <h3><i>Nav rezervētu pasākumu.</i></h3>
          @else
                <thead>
                  <tr>
                    <th scope="col" class="content">Datums</th>
                    <th class="space" scope="col">Pasākums</th>
                    <th class="space" scope="col"></th>
                  </tr>
                </thead>
          
                @foreach ($reservations as $r){{-- līdzīgi kā slierī izvada pasākumus (home.blade.php) --}}
                <tbody class="searchcontent"> {{ geteventbyreservation($r->id,$event) }}
                  <tr>
                    <td class="top clickshow">
                        <a class='divlink' href="{{ route('showreservation',$r->id) }}"></a>
                        <div class="eventdate">
                            <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($event->Datefrom) }}</span></div>
                            <div class="eventmonth block h-center v-center"><span class="pagmonth{{ $counter }}">Mēnesis</span></div>{{-- counter Lai izavadītos precīzs mēnesis  --}}
                        </div>
                    </td>
                    <td class="top space eventinfo clickshow">
                        <a class='divlink' href="{{ route('showreservation',$r->id) }}"></a>
                        <h5 class="eventtitle">{{ $event->Title }}</h5>
                        <p>Kad: <span class="searcheventdate">{{ geteventdate($event->Datefrom) }}</span></p><span class="searcheventdate" id='eventdate{{ $counter++ }}'style="display:none">{{ $event->Datefrom }}</span>
                        <p>Kur: <span class="eventaddress">{{ $event->Address }}</span></p>
                        <i >Biļešu skaits: <span class="ticketsnumber">{{ $r->Tickets }}</span></i>
                    </td>
                    <td class="space" @if($event->Editable == 0 && Auth::user()->hasRole('User')) colspan="2" style="text-align: center" @elseif(checkExpired($r->EventID)) colspan="2" style="text-align: center" @endif><a href="{{ route('showreservation',$r->id) }}" class="button">Apskatīt</a></td>
                    @if($event->Editable == 1 || Auth::user()->hasRole('Admin'))
                      @if(!checkExpired($r->EventID))
                    <td class="space"><a href="{{ route('showreservationedit',$r->id) }}" class="button">Rediģēt</a></td>
                      @endif
                    @endif
                  </tr>
                </tbody>
                @endforeach
          @endif
        </table>
        </div> {{-- paginācijas linki --}}
        <span style="display:none" id="counter">{{ $counter }}</span>
        @if($reservations->count() > 0)
        <ul class="slider-months">
            @for ($i = 0; $i < count($pagenumber); $i++)
                <li class="slider-months_item">
                    <a href="{{ route('reservationusers',$i + 1) }}"  class="button">{{ $i + 1 }}</a>
                </li>
            @endfor
              </ul>
              <br>
        @endif
    </div>
@endsection