@extends('welcome')
@section('content')

@if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check()) 
                <a href="{{ route('profile.index') }}">{{Auth::user()->First_name}} Profile</a>
                <a href="/">Home</a>
                <a href="/logout" {{-- izlogošanas iebūvēta funkcionalitāte --}}
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
            <a href="{{ route('showlogin') }}">Login</a>
            <a href="{{ route('showregister') }}">Registser</a>
            @endif
        </div>
        @endif
            <div class="content">
                @if(session()->has('message'))
                  <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p class="mb-0">{{ session()->get('message') }}</p>
                  </div>
                @endif
                @if(session()->get('info') === 'VIP')
                <div class="alert alert-dismissible alert-primary">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Tika izveidots VIP pasākums!</strong><p class="mb-0">Linku uz izveidoto VIP pasākumu var atrast slaiderī pie pasākuma,rediģēšanas formā un pie pasākuma apskata</p>
                </div>
                @endif
                <div class="title m-b-md">
                  
                    Pasākumi
                </div>
                
            </div>
            @if (Auth::check())
            <div class="top-left links">
            @if (Auth::user()->hasRole('Admin')) {{-- tikai admini var skatīti šo --}}
            <a href="{{ route('showcreate') }}">Create event</a>
            <a href="{{ route('showsavedevents',1) }}">Saved events</a>
            @endif
            <a href="{{ route('reservationusers',1) }}">My reservations</a>
            </div>
            @endif
        
        
        <div class="contain">
            <div class="slidercontainer color-green">
              <a class="prev"></a> {{-- tekošais mēnesis --}}
              <span class="month uppercase bold">Mēnesis</span> 
              <a class="next" href></a>
            </div>
        <div class="slider"> {{-- slaiders --}}
          
          @for($i = 0;$i <= $pages;$i++) 

          <div class="contain"> 
            <table class="eventtable">
              @if (empty($data[$i])) {{-- Ja šajā mēnesī nav pasākumu rāda paziņojumu --}}
                <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
              @else {{-- jeb izvada visus pasākumus --}}
                    <thead>
                      
                      <tr>
                        <th scope="col" class="content">Datums</th>
                        <th class="space" scope="col">Pasākums</th>
                        <th class="space" scope="col"></th>
                      </tr>
                    </thead>
                    @foreach ($data[$i] as $d) {{-- izvada piecus pēc šī meneša --}}
                    <tbody>
                      <tr>

                      </tr>
                      <td rowspan="2" class="top clickshow"><a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                        <div class="eventdate"> {{-- geteventdate,geteventd funckijas helpers.php failā lai korrekti izvadīt informāciju --}}
                            <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($d->Datefrom) }}</span></div>
                            <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                        </div>
                      </td>
                      <td rowspan="2" class="top space eventinfo clickshow">
                        <a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                        <h5>{{ $d->Title }}
                        @if($d->VIP == 1) {{countbyoneVIP($count)}}
                        (VIP)
                        @if(Auth::check() && Auth::user()->hasRole('Admin'))
                        <button type="button" class="vip btn btn-secondary clippy homecopybtn{{ $count }}">
                            <input type="text" id="linkcopy{{ $count }}"class="linkcopy" value="{{ route('showreservationcreate', ['id' => $d->id,'extension' => $d->linkcode]) }}">
                            <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="15" height="15">
                        </button>
                        <div class="vip" id='popover{{ $count }}'></div>
                        @endif
                        @endif
                        @if(reservinfo($d->id)[0] == 0 && $d->Tickets != -999)
                        (Biļetes beidzās)
                        @endif</h5>
                        <p>Kad: {{ geteventdate($d->Datefrom) }}</p>
                        <p>Kur: {{ $d->Address }}</p>
                        <i>{{ $d->Anotation }}</i>
                      </td>
                      @if(Auth::check() && Auth::user()->hasRole('Admin'))
                      <td style="text-align:center;" colspan="2" class="showreserv">
                        <a href="{{ route('showreservationadmins',$d->id) }}" class="button reservshow">Apskatīt rezervācijas</a>
                      </td>
                      @endif
                      <tr>
                        @if(Auth::check())
  
                        <td class="sliderbutton" @if(Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$d->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                          style="text-align: center" @endif class="space">
                        @if(Auth::user()->hasRole('Admin'))

                        @endif
                        @if((reservinfo($d->id)[0] == 0 && $d->Tickets != -999) || $d->VIP == 1)
                       <a href="{{ route('showevent',$d->id) }}" class="button">
                        Apskatīt </a>
                        @else
                       <a href="{{ route('showreservationcreate',['id' => $d->id, 'extension' => $d->linkcode]) }}" class="button ">
                        Rezervēt
                        @endif</a></td>
                        @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$d->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                        <td class="sliderbutton space"><a href="{{ route('showedit',$d->id) }}" class="button ">Rediģēt</a></td>
                        @else <td></td>
                        @endif
                        @else <td class="sliderbutton space"><a href="{{ route('showevent',$d->id) }}" class="button">Apskatīt</a>
                        @endif
                      </tr>
                    </tbody>
                    @endforeach
              @endif
            </table>
            </div>
            @endfor
        </div>

        <span id="countVIP" style="display:none">{{$count}}</span>

        <ul class="slider-months"> {{-- mēneši kurus apstrādā javascript --}}
          @for($i = 0;$i <= $pages;$i++)
          <li class="slider-months_item">
            <a href="" class="button" id="month{{ $i }}" data-slide-index="{{ $i }}"></a>
          </li>
          @endfor
        </ul>

        
@endsection