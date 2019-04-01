@extends('welcome')
@section('content')

@if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check()) 
                <a href="{{ route('profile.index') }}">{{Auth::user()->First_name}} Profile</a>
                <a href="/">Home</a>
                <a href="{{ route('logout') }}" {{-- izlogošanas iebūvēta funkcionalitāte --}}
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
          
          <div class="contain"> 
            <table class="eventtable">
              @if (empty($data)) {{-- Ja šajā mēnesī nav pasākumu rāda paziņojumu --}}
                <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
              @else {{-- jeb izvada visus pasākumus --}}
                    <thead>
                      
                      <tr>
                        <th scope="col" class="content">Datums</th>
                        <th class="space" scope="col">Pasākums</th>
                        <th class="space" scope="col"></th>
                      </tr>
                    </thead>
                    @foreach ($data as $d) {{-- izvada piecus pēc šī meneša --}}
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td colspan="2" style="text-align:center;"><a href="{{ route('showreservationadmins',$d->id) }}" class="button reservshow">Apskatīt rezervācijas</a></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td class="top clickshow">
                            <a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                            <div class="eventdate"> {{-- geteventdate,geteventd funckijas helpers.php failā lai korrekti izvadīt informāciju --}}
                                <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($d->Datefrom) }}</span></div>
                                <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                            </div>
                        </td>
                        <td class="top space eventinfo clickshow">
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
                        @if(Auth::check())
  
                        <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$d->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                          style="text-align: center" @endif class="space" >
                        @if(Auth::user()->hasRole('Admin'))
                        



                        @endif
                        @if((reservinfo($d->id)[0] == 0 && $d->Tickets != -999) || $d->VIP == 1)
                        <a href="{{ route('showevent',$d->id) }}" class="button">
                        Apskatīt </a>
                        @else
                        <a href="{{ route('showreservationcreate',['id' => $d->id, 'extension' => $d->linkcode]) }}" class="button">
                        Rezervēt
                        @endif</a></td>
                        @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$d->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                        <td class="space"><a href="{{ route('showedit',$d->id) }}" class="button">Rediģēt</a></td>
                        @else <td></td>
                        @endif
                        @else <td class="space"><a href="{{ route('showevent',$d->id) }}" class="button">Apskatīt</a>
                        @endif
                      </tr>
                    </tbody>
                    @endforeach
              @endif
            </table>
            </div>

            <div class="contain"> 
              <table class="eventtable">
                @if (empty($dataplus1))
                  <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
                @else
                      <thead>
                        <tr>
                          <th scope="col" class="content">Datums</th>
                          <th class="space" scope="col">Pasākums</th>
                          <th class="space" scope="col"></th>
                        </tr>
                      </thead>
                
                      @foreach ($dataplus1 as $dp1)
    
                      <tbody>
                        <tr>
                          <td class="top">
                            <a href="{{ route('showevent',$dp1->id) }}"></a>
                              <div class="eventdate">
                                  <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp1->Datefrom) }}</span></div>
                                  <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                              </div>
                          </td>
                          <td class="top space eventinfo">
                            <a href="{{ route('showevent',$dp1->id) }}"></a>
                              <h5>{{ $dp1->Title }}
                              @if($dp1->VIP == 1){{countbyoneVIP($count)}}
                              (VIP)
                              @if(Auth::check() && Auth::user()->hasRole('Admin'))
                                      <button type="button" class="vip btn btn-secondary clippy homecopybtn{{ $count }}">
                                      <input type="text" id="linkcopy{{ $count }}"class="linkcopy" value="{{ route('showreservationcreate', ['id' => $dp1->id,'extension' => $dp1->linkcode]) }}">
                                      <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="15" height="15">
                                      </button>
                                      <div class="vip" id='popover{{ $count }}'></div>
                                      @endif
                              @endif
                              @if(reservinfo($dp1->id)[0] == 0 && $dp1->Tickets != -999)
                              (Biļetes beidzās)
                              @endif</h5>
                              <p>Kad: {{ geteventdate($dp1->Datefrom) }}</p>
                              <p>Kur: {{ $dp1->Address }}</p>
                              <i>{{ $dp1->Anotation }}</i>
                          </td>
                          @if(Auth::check())
                          <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$dp1->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                            style="text-align: center" @endif class="space" >
                          @if(reservinfo($dp1->id)[0] == 0 && $dp1->Tickets != -999 || $dp1->VIP == 1)
                          <a href="{{ route('showevent',$dp1->id) }}" class="button">
                          Apskatīt </a>
                          @else
                          <a href="{{ route('showreservationcreate',['id' => $dp1->id, 'extension' => $dp1->linkcode]) }}" class="button">
                          Rezervēt
                          @endif</a></td>
                          @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp1->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                          <td class="space"><a href="{{ route('showedit',$dp1->id) }}" class="button">Rediģēt</a></td>
                          @else <td></td>
                          @endif
                          @else <td class="space"><a href="{{ route('showevent',$dp1->id) }}" class="button">Apskatīt</a>
                          @endif
                        </tr>
                      </tbody>
                      @endforeach
                @endif
              </table>
              </div>

              <div class="contain"> 
                <table class="eventtable">
                  @if (empty($dataplus2))
                    <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
                  @else 
                        <thead>
                          <tr>
                            <th scope="col" class="content">Datums</th>
                            <th class="space" scope="col">Pasākums</th>
                            <th class="space" scope="col"></th>
                          </tr>
                        </thead>
                  
                        @foreach ($dataplus2 as $dp2)
      
                        <tbody>
                          <tr>
                            <td class="top">
                              <a href="{{ route('showevent',$dp2->id) }}"></a>
                                <div class="eventdate">
                                    <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp2->Datefrom) }}</span></div>
                                    <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                </div>
                            </td>
                            <td class="top space eventinfo">
                              <a href="{{ route('showevent',$dp2->id) }}"></a>
                                <h5>{{ $dp2->Title }}
                                @if($dp2->VIP == 1){{countbyoneVIP($count)}}
                                (VIP)
                                @if(Auth::check() && Auth::user()->hasRole('Admin'))
                                      <button type="button" class="vip btn btn-secondary clippy homecopybtn{{ $count }}">
                                      <input type="text" id="linkcopy{{ $count }}"class="linkcopy" value="{{ route('showreservationcreate', ['id' => $dp2->id,'extension' => $dp2->linkcode]) }}">
                                      <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="15" height="15">
                                      </button>
                                      <div class="vip" id='popover{{ $count }}'></div>
                                      @endif
                                @endif
                                @if(reservinfo($dp2->id)[0] == 0 && $dp2->Tickets != -999)
                                (Biļetes beidzās)
                                @endif</h5>
                                <p>Kad: {{ geteventdate($dp2->Datefrom) }}</p>
                                <p>Kur: {{ $dp2->Address }}</p>
                                <i>{{ $dp2->Anotation }}</i>
                            </td>
                            @if(Auth::check())
                            <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$dp2->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                              style="text-align: center" @endif class="space" >
                            @if(reservinfo($dp2->id)[0] == 0 && $dp2->Tickets != -999 || $dp2->VIP == 1)
                            <a href="{{ route('showevent',$dp2->id) }}" class="button">
                            Apskatīt </a>
                            @else
                            <a href="{{ route('showreservationcreate',['id' => $dp2->id, 'extension' => $dp2->linkcode]) }}" class="button">
                            Rezervēt
                            @endif</a></td>
                            @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp2->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                            <td class="space"><a href="{{ route('showedit',$dp2->id) }}" class="button">Rediģēt</a></td>
                            @else <td></td>
                            @endif
                            @else <td class="space"><a href="{{ route('showevent',$dp2->id) }}" class="button">Apskatīt</a>
                            @endif
                          </tr>
                        </tbody>
                        @endforeach
                  @endif
                </table>
                </div>

                <div class="contain"> 
                  <table class="eventtable">
                    @if (empty($dataplus3))
                      <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
                    @else  
                          <thead>
                            <tr>
                              <th scope="col" class="content">Datums</th>
                              <th class="space" scope="col">Pasākums</th>
                              <th class="space" scope="col"></th>
                            </tr>
                          </thead>
                    
                          @foreach ($dataplus3 as $dp3)
        
                          <tbody>
                            <tr>
                              <td class="top">
                                <a href="{{ route('showevent',$dp3->id) }}"></a>
                                  <div class="eventdate">
                                      <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp3->Datefrom) }}</span></div>
                                      <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                  </div>
                              </td>
                              <td class="top space eventinfo">
                                <a href="{{ route('showevent',$dp3->id) }}"></a>
                                  <h5>{{ $dp3->Title }}
                                  @if($dp3->VIP == 1){{countbyoneVIP($count)}}
                                  (VIP)
                                  @if(Auth::check() && Auth::user()->hasRole('Admin'))
                                      <button type="button" class="vip btn btn-secondary clippy homecopybtn{{ $count }}">
                                      <input type="text" id="linkcopy{{ $count }}"class="linkcopy" value="{{ route('showreservationcreate', ['id' => $dp3->id,'extension' => $dp3->linkcode]) }}">
                                      <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="15" height="15">
                                      </button>
                                      <div class="vip" id='popover{{ $count }}'></div>
                                      @endif
                                  @endif
                                  @if(reservinfo($dp3->id)[0] == 0 && $dp3->Tickets != -999)
                                  (Biļetes beidzās)
                                  @endif</h5>
                                  <p>Kad: {{ geteventdate($dp3->Datefrom) }}</p>
                                  <p>Kur: {{ $dp3->Address }}</p>
                                  <i>{{ $dp3->Anotation }}</i>
                              </td>
                              @if(Auth::check())
                              <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$dp3->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                                style="text-align: center" @endif class="space" >
                              @if(reservinfo($dp3->id)[0] == 0 && $dp3->Tickets != -999 || $dp3->VIP == 1)
                              <a href="{{ route('showevent',$dp3->id) }}" class="button">
                              Apskatīt </a>
                              @else
                              <a href="{{ route('showreservationcreate',['id' => $dp3->id, 'extension' => $dp3->linkcode]) }}" class="button">
                              Rezervēt
                              @endif</a></td>
                              @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp3->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                              <td class="space"><a href="{{ route('showedit',$dp3->id) }}" class="button">Rediģēt</a></td>
                              @else <td></td>
                              @endif
                              @else <td class="space"><a href="{{ route('showevent',$dp3->id) }}" class="button">Apskatīt</a>
                              @endif
                            </tr>
                          </tbody>
                          @endforeach
                    @endif
                  </table>
                  </div>

                  <div class="contain"> 
                    <table class="eventtable">
                      @if (empty($dataplus4))
                        <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
                      @else   
                            <thead>
                              <tr>
                                <th scope="col" class="content">Datums</th>
                                <th class="space" scope="col">Pasākums</th>
                                <th class="space" scope="col"></th>
                              </tr>
                            </thead>
                      
                            @foreach ($dataplus4 as $dp4)
          
                            <tbody>
                              <tr>
                                <td class="top">
                                  <a href="{{ route('showevent',$dp4->id) }}"></a>
                                    <div class="eventdate">
                                        <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp4->Datefrom) }}</span></div>
                                        <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                    </div>
                                </td>
                                <td class="top space eventinfo">
                                  <a href="{{ route('showevent',$dp4->id) }}"></a>
                                    <h5>{{ $dp4->Title }}
                                    @if($dp4->VIP == 1){{countbyoneVIP($count)}}
                                    (VIP)
                                    @if(Auth::check() && Auth::user()->hasRole('Admin'))
                                      <button type="button" class="vip btn btn-secondary clippy homecopybtn{{ $count }}">
                                      <input type="text" id="linkcopy{{ $count }}"class="linkcopy" value="{{ route('showreservationcreate', ['id' => $dp4->id,'extension' => $dp4->linkcode]) }}">
                                      <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="15" height="15">
                                      </button>
                                      <div class="vip" id='popover{{ $count }}'></div>
                                      @endif
                                    @endif
                                    @if(reservinfo($dp4->id)[0] == 0 && $dp4->Tickets != -999)
                                    (Biļetes beidzās)
                                    @endif</h5>
                                    <p>Kad: {{ geteventdate($dp4->Datefrom) }}</p>
                                    <p>Kur: {{ $dp4->Address }}</p>
                                    <i>{{ $dp4->Anotation }}</i>
                                </td>
                                @if(Auth::check())
                                <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$dp4->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                                  style="text-align: center" @endif class="space" >
                                @if(reservinfo($dp4->id)[0] == 0 && $dp4->Tickets != -999 || $dp4->VIP == 1)
                                <a href="{{ route('showevent',$dp4->id) }}" class="button">
                                Apskatīt </a>
                                @else
                                <a href="{{ route('showreservationcreate',['id' => $dp4->id, 'extension' => $dp4->linkcode]) }}" class="button">
                                Rezervēt
                                @endif</a></td>
                                @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp4->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                                <td class="space"><a href="{{ route('showedit',$dp4->id) }}" class="button">Rediģēt</a></td>
                                @else <td></td>
                                @endif
                                @else <td class="space"><a href="{{ route('showevent',$dp4->id) }}" class="button">Apskatīt</a>
                                @endif
                              </tr>
                            </tbody>
                            @endforeach
                      @endif
                    </table>
                    </div>
                    
                    <div class="contain"> 
                      <table class="eventtable">
                        @if (empty($dataplus5))
                          <h3><i>Nav plānotu pasākumu šajā mēnesī.</i></h3>
                        @else   
                              <thead>
                                <tr>
                                  <th scope="col" class="content">Datums</th>
                                  <th class="space" scope="col">Pasākums</th>
                                  <th class="space" scope="col"></th>
                                </tr>
                              </thead>
                        
                              @foreach ($dataplus5 as $dp5)
            
                              <tbody>
                                <tr>
                                  <td class="top clickshow">
                                    <a href="{{ route('showevent',$dp5->id) }}"></a>
                                      <div class="eventdate">
                                          <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp5->Datefrom) }}</span></div>
                                          <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                      </div>
                                  </td>
                                  <td class="top space eventinfo clickshow">
                                    <a href="{{ route('showevent',$dp5->id) }}"></a>
                                      <h5>{{ $dp5->Title }}
                                      @if($dp5->VIP == 1){{countbyoneVIP($count)}}
                                      (VIP)
                                      @if(Auth::check() && Auth::user()->hasRole('Admin'))
                                      <button type="button" class="vip btn btn-secondary clippy homecopybtn{{ $count }}">
                                      <input type="text" id="linkcopy{{ $count }}"class="linkcopy" value="{{ route('showreservationcreate', ['id' => $dp5->id,'extension' => $dp5->linkcode]) }}">
                                      <img id='imgcopy' src="{{ asset('clippy.svg') }}" width="15" height="15">
                                      </button>
                                      <div class="vip" id='popover{{ $count }}'></div>
                                      @endif
                                      @endif
                                      @if(reservinfo($d->id)[0] == 0 && $dp5->Tickets != -999)
                                      (Biļetes beidzās)
                                      @endif</h5>
                                      <p>Kad: {{ geteventdate($dp5->Datefrom) }}</p>
                                      <p>Kur: {{ $dp5->Address }}</p>
                                      <i>{{ $dp5->Anotation }}</i>
                                  </td>
                                  @if(Auth::check())
                                  <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$dp5->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                                    style="text-align: center" @endif class="space" >
                                  @if(reservinfo($dp5->id)[0] == 0 && $dp5->Tickets != -999 || $dp5->VIP == 1)
                                  <a href="{{ route('showevent',$dp5->id) }}" class="button">
                                  Apskatīt </a>
                                  @else
                                  <a href="{{ route('showreservationcreate',['id' => $dp5->id, 'extension' => $dp5->linkcode]) }}" class="button">
                                  Rezervēt
                                  @endif</a></td>
                                  @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp5->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                                  <td class="space"><a href="{{ route('showedit',$dp5->id) }}" class="button">Rediģēt</a></td>
                                  @else <td></td>
                                  @endif
                                  @else <td class="space"><a href="{{ route('showevent',$dp5->id) }}" class="button">Apskatīt</a>
                                  @endif
                                </tr>
                              </tbody>
                              @endforeach
                        @endif
                      </table>
                      </div>        
        </div>
        <span id="countVIP" style="display:none">{{$count}}</span>
        <ul class="slider-months"> {{-- mēneši kurus apstrādā javascript --}}
          <li class="slider-months_item">
            <a href="" class="button" id="month0" data-slide-index="0"></a>
          </li>
          <li class="slider-months_item">
            <a href="" class="button" id="month1" data-slide-index="1"></a>
          </li>
          <li class="slider-months_item">
            <a href="" class="button" id="month2" data-slide-index="2"></a>
          </li>
          <li class="slider-months_item">
            <a href="" class="button" id="month3" data-slide-index="3"></a>
          </li>
          <li class="slider-months_item">
            <a href="" class="button" id="month4" data-slide-index="4"></a>
          </li>
          <li class="slider-months_item">
            <a href="" class="button" id="month5" data-slide-index="5"></a>
          </li>
        </ul>

        </div>
@endsection