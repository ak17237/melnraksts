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
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
                <div class="title m-b-md">
                  
                    Pasākumi
                </div>
                
            </div>
            @if (Auth::check())
            @if (Auth::user()->hasRole('Admin')) {{-- tikai admini var skatīti šo --}}
            <div class="top-left links">
            <a href="{{ route('showcreate') }}">Create event</a>
            <a href="/saved-events-1">Saved events</a>
            @endif
            @endif
        </div>
        
        
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
                        <td class="top">
                            <a href="{{ route('showevent',$d->id) }}"></a>
                            <div class="eventdate"> {{-- geteventdate,geteventd funckijas helpers.php failā lai korrekti izvadīt informāciju --}}
                                <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($d->Datefrom) }}</span></div>
                                <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                            </div>
                        </td>
                        <td class="top space eventinfo">
                            <a href="{{ route('showevent',$d->id) }}"></a>
                            <h5>{{ $d->Title }}
                            @if(reservinfo($d->id)[0] == 0 && $d->Tickets != -999)
                            (Biļetes beidzās)
                            @endif</h5>
                            <p>Kad: {{ geteventdate($d->Datefrom) }}</p>
                            <p>Kur: {{ $d->Address }}</p>
                            <i>{{ $d->Anotation }}</i>
                        </td>
                        @if(Auth::check())
                        <td @if (Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$d->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                          style="text-align: center" @endif class="space" ><a href="{{ route('showreservationcreate',$d->id) }}" class="button">
                        @if(reservinfo($d->id)[0] == 0 && $d->Tickets != -999)
                        Apskatīt
                        @else
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
                              <div class="eventdate">
                                  <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp1->Datefrom) }}</span></div>
                                  <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                              </div>
                          </td>
                          <td class="top space eventinfo">
                              <h5>{{ $dp1->Title }}
                              @if(reservinfo($dp1->id)[0] == 0 && $dp1->Tickets != -999)
                              (Biļetes beidzās)
                              @endif</h5>
                              <p>Kad: {{ geteventdate($dp1->Datefrom) }}</p>
                              <p>Kur: {{ $dp1->Address }}</p>
                              <i>{{ $dp1->Anotation }}</i>
                          </td>
                          <td class="space"><a href="{{ route('showreservationcreate',$dp1->id) }}" class="button">
                          @if(reservinfo($dp1->id)[0] == 0 && $dp1->Tickets != -999)
                          Apskatīt
                          @else
                          Rezervēt
                          @endif</a></td>
                          @if (Auth::check())
                          @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp1->id))
                          <td class="space"><a href="{{ route('showedit',$dp1->id) }}" class="button">Rediģēt</a></td>
                          @endif
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
                                <div class="eventdate">
                                    <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp2->Datefrom) }}</span></div>
                                    <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                </div>
                            </td>
                            <td class="top space eventinfo">
                                <h5>{{ $dp2->Title }}
                                @if(reservinfo($dp2->id)[0] == 0 && $dp2->Tickets != -999)
                                (Biļetes beidzās)
                                @endif</h5>
                                <p>Kad: {{ geteventdate($dp2->Datefrom) }}</p>
                                <p>Kur: {{ $dp2->Address }}</p>
                                <i>{{ $dp2->Anotation }}</i>
                            </td>
                            <td class="space"><a href="{{ route('showreservationcreate',$dp2->id) }}" class="button">
                            @if(reservinfo($dp2->id)[0] == 0 && $dp2->Tickets != -999)
                            Apskatīt
                            @else
                            Rezervēt
                            @endif</a></td>
                            @if (Auth::check())
                            @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp2->id))
                            <td class="space"><a href="{{ route('showedit',$dp2->id) }}" class="button">Rediģēt</a></td>
                            @endif
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
                                  <div class="eventdate">
                                      <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp3->Datefrom) }}</span></div>
                                      <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                  </div>
                              </td>
                              <td class="top space eventinfo">
                                  <h5>{{ $dp3->Title }}
                                  @if(reservinfo($dp3->id)[0] == 0 && $dp3->Tickets != -999)
                                  (Biļetes beidzās)
                                  @endif</h5>
                                  <p>Kad: {{ geteventdate($dp3->Datefrom) }}</p>
                                  <p>Kur: {{ $dp3->Address }}</p>
                                  <i>{{ $dp3->Anotation }}</i>
                              </td>
                              <td class="space"><a href="{{ route('showreservationcreate',$dp3->id) }}" class="button">
                              @if(reservinfo($dp3->id)[0] == 0 && $dp3->Tickets != -999)
                              Apskatīt
                              @else
                              Rezervēt
                              @endif</a></td>
                              @if (Auth::check())
                              @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp3->id))
                              <td class="space"><a href="{{ route('showedit',$dp3->id) }}" class="button">Rediģēt</a></td>
                              @endif
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
                                    <div class="eventdate">
                                        <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp4->Datefrom) }}</span></div>
                                        <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                    </div>
                                </td>
                                <td class="top space eventinfo">
                                    <h5>{{ $dp4->Title }}
                                    @if(reservinfo($dp4->id)[0] == 0 && $dp4->Tickets != -999)
                                    (Biļetes beidzās)
                                    @endif</h5>
                                    <p>Kad: {{ geteventdate($dp4->Datefrom) }}</p>
                                    <p>Kur: {{ $dp4->Address }}</p>
                                    <i>{{ $dp4->Anotation }}</i>
                                </td>
                                <td class="space"><a href="{{ route('showreservationcreate',$dp4->id) }}" class="button">
                                @if(reservinfo($dp4->id)[0] == 0 && $dp4->Tickets != -999)
                                Apskatīt
                                @else
                                Rezervēt
                                @endif</a></td>
                                @if (Auth::check())
                                @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$dp4->id))
                                <td class="space"><a href="{{ route('showedit',$dp4->id) }}" class="button">Rediģēt</a></td>
                                @endif
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
                                  <td class="top">
                                      <div class="eventdate">
                                          <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($dp5->Datefrom) }}</span></div>
                                          <div class="eventmonth block h-center v-center"><span class="month">Mēnesis</span></div>
                                      </div>
                                  </td>
                                  <td class="top space eventinfo">
                                      <h5>{{ $dp5->Title }}
                                      @if(reservinfo($d->id)[0] == 0 && $dp5->Tickets != -999)
                                      (Biļetes beidzās)
                                      @endif</h5>
                                      <p>Kad: {{ geteventdate($dp5->Datefrom) }}</p>
                                      <p>Kur: {{ $dp5->Address }}</p>
                                      <i>{{ $dp5->Anotation }}</i>
                                  </td>
                                  <td class="space"><a href="{{ route('showreservationcreate',$dp5->id) }}" class="button">
                                  @if(reservinfo($dp5->id)[0] == 0 && $dp5->Tickets != -999)
                                  Apskatīt
                                  @else
                                  Rezervēt
                                  @endif</a></td><br>
                                  @if (Auth::check())
                                  @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$d->id))
                                  <td class="space"><a href="{{ route('showedit',$dp5->id) }}" class="button">Rediģēt</a></td>
                                  @endif
                                  @endif
                                </tr>
                              </tbody>
                              @endforeach
                        @endif
                      </table>
                      </div>        
        </div>
        
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