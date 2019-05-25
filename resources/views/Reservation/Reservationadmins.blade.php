@extends('welcome')
@section('PageTitle',$myevent->Title . ' rezervācijas')
@section('content')
<div class="container">
  <div class="contain">     
    @if(session()->has('message'))
      <div class="alert alert-dismissible alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>{{ session()->get('message') }}</p>
      </div>
    @endif
    <div class="slidercontainer color-green">
      <a class="prev"></a> {{-- Pasākuma nosaukums --}}
      <span class="uppercase bold">{{ $myevent->Title }}</span> 
      <a class="next" href></a>
    </div>
    <div class="reservationslider"> {{-- slaiders --}}
      @if ($count == 0) {{-- Ja šajā pasākumā nav rezervāciju rāda paziņojumu --}}
        <h3><i>Nav rezervācijas šim pasākumam.</i></h3>
      @else {{-- jeb izvada visas rezervācijas --}}
        @for ($i = 0; $i < $count; $i += $number)
    
          <div class="contain"> 
            <table class="eventtable">
              <thead>
                <tr>
                  <th scope="col" class="content">Bilde</th>
                  <th class="space" scope="col">Vārds</th>
                  <th class="space" scope="col"></th>
                </tr>
              </thead>
              @if(($count - $i) < $number) <code hidden>{{ $tempnumber = $count - $i }}</code> @endif{{-- Izvada tik rezervācijas cik paredzēts vienā lapā --}}
              @for ($j = 0;$j < $tempnumber;$j++)
                <tbody>
                  <tr>
                    <td class="top clickshow">
                      @if(Storage::disk('avatar')->has(getuserbyid($reservation[$i+$j]->user_id)->Avatar))
                      <a href="/profile-avatar/{{getuserbyid($reservation[$i+$j]->user_id)->Avatar}}">
                        <img src="/profile-avatar/{{getuserbyid($reservation[$i+$j]->user_id)->Avatar}}">
                      </a>
                      @else
                      <a href="/png/Empty-Avatar.png">
                        <img src="/png/Empty-Avatar.png">
                      </a>
                      @endif
                    </td>
                    <td class="top space eventinfo clickshow">
                      <a class='divlink' href="{{ route('showreservation',$reservation[$i+$j]->id) }}"></a>
                      <h5>{{ getuserbyid($reservation[$i+$j]->user_id)->First_name }} {{ getuserbyid($reservation[$i+$j]->user_id)->Last_name }}</h5>
                      <i>Biļešu skaits: {{ $reservation[$i+$j]->Tickets }}</i>
                    </td>
                    <td class="space" @if(checkExpired($myevent->id)) colspan="2" style="text-align: center" @endif><a href="{{ route('showreservation',$reservation[$i+$j]->id) }}" class="button reservsmall">Apskatīt</a>
                      @if(!checkExpired($myevent->id))
                    <td class="space"><a href="{{ route('showreservationedit',$reservation[$i+$j]->id) }}" class="button reservsmall">Rediģēt</a>
                      @endif
                  </tr>
                </tbody>
              @endfor
            </table>
          </div>
        @endfor
      @endif
    </div>
    <ul class="slider-reservation"> {{-- Slaidera lapas kurām var piekļūt --}}
        @for($i = 0;$i < ceil($count / $number);$i++)
          <li class="slider-reservation_item"> 
            <a href="" class="button" data-slide-index="{{ $i }}">{{ $i + 1 }}</a>
          </li>
        @endfor
    </ul>
  </div>
</div>
<br>
@endsection    