@extends('welcome')
@section('content')

            <div class="content">
                @if(session()->has('message'))
                <br><br>
                  <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p class="mb-0">{{ session()->get('message') }}</p>
                  </div>
                @endif
                @if(session()->get('info') === 'VIP')
                <br>
                <div class="alert alert-dismissible alert-primary">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Tika izveidots VIP pasākums!</strong><p class="mb-0">Linku uz izveidoto VIP pasākumu var atrast slaiderī pie pasākuma,rediģēšanas formā un pie pasākuma apskata</p>
                </div>
                @endif
                <div class="title m-b-md">
                  
                    Pasākumi
                </div>
                
            </div>      
        
        <div class="contain">
          @if(Auth::check())
          <div class="tab">
              <button class="centered" id="slider">Gaidāmie pasākumi</button>
              <button class="centered" id="historyslider">Pagājušie pasākumi</button>
          </div>
          @endif
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
                            <tr @if(date("Y-m-d") >= $d->Datefrom) class="expiredevent" @endif @if(Auth::check() && Auth::user()->hasRole('User') || !Auth::check()) style="height:1px;" @endif>
      
                            
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
                              <a href="{{ route('showreservationadmins',$d->id) }}" class="button reservshow today">Apskatīt rezervācijas</a>
                            </td>
                            @endif
                          </tr>
                            <tr @if(date("Y-m-d") >= $d->Datefrom) class="expiredevent" @endif>
                              @if(Auth::check())
                                @if(Auth::user()->hasRole('Admin') && date('Y-m-d') >= $d->Datefrom && date('Y-m-d') <= $d->Dateto)
                                <td style="text-align:center;" colspan="2" class="showreserv">
                                  <a href="{{ route('showqrcode',$d->id) }}" class="sliderscan button reservshow today">Noskanēt biļetes</a>
                                </td>
                                @else
                              <td class="sliderbutton" @if(Auth::user()->hasRole('Admin') && !checkAuthor(Auth::user()->email,$d->id)) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                                style="text-align: center" @endif class="space">
                              @if((reservinfo($d->id)[0] == 0 && $d->Tickets != -999) || $d->VIP == 1)
                             <a href="{{ route('showevent',$d->id) }}" class="button" @if(Auth::user()->hasRole('User')) style="width: 124px;font-size: 17px;"@endif 
                              @if(!checkAuthor(Auth::user()->email,$d->id)) style="margin: 0 60px 0 60px;" @endif>
                              Apskatīt </a>
                              @else
                             <a href="{{ route('showreservationcreate',['id' => $d->id, 'extension' => $d->linkcode]) }}" class="button" @if(Auth::user()->hasRole('User')) style="width: 124px;font-size: 17px;" @endif
                              @if(!checkAuthor(Auth::user()->email,$d->id)) style="margin: 0 60px 0 60px;" @endif>
                              Rezervēt
                              @endif</a></td>
                              @if (Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$d->id)) {{-- Tikai administrācijas piekļuve un tikai pasākuma autoram--}}
                              <td class="sliderbutton space"><a href="{{ route('showedit',$d->id) }}" class="button ">Rediģēt</a></td>
                              @endif
                              @endif
                              @else <td class="sliderbutton space"><a href="{{ route('showevent',$d->id) }}" class="button" style="width: 124px;font-size: 17px;">Apskatīt</a>
                              @endif
                            </tr>
                          </tbody>
                          @endforeach
                    @endif
                  </table>
                  </div>
                  
                  @endfor
                  
              </div>

        <div class="historyslider"> {{--  pagājušo pasākumu slaiders --}}
          
            @for($i = -$pages;$i <= 0;$i++) 
  
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
                      @foreach ($data[$i] as $d) {{-- izvada piecus pirms šī meneša --}}
                      @if(date("Y-m-d") >= $d->Datefrom)
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
                          <h5>{{ $d->Title }}</h5>
                          <p>Kad: {{ geteventdate($d->Datefrom) }}</p>
                          <p>Kur: {{ $d->Address }}</p>
                          <i>{{ $d->Anotation }}</i>
                        </td>
                        @if(Auth::check() && checkAttendance(Auth::user()->id,$d->id) || Auth::user()->hasRole('Admin'))
                        <td style="text-align:center;" colspan="2" class="showreserv">
                          <a href="{{ route('showgallery',$d->id) }}" class="button reservshow" @if(Auth::check() && Auth::user()->hasRole('User')) style="position:unset;" @endif>Apskatīt galeriju</a>
                        </td>
                        @endif
                        <tr>
                          @if(Auth::check())
                          <td class="sliderbutton" @if(Auth::user()->hasRole('User')) colspan="2" {{-- ja nav piekļuves pogai lai būtu centrēts --}}
                            style="text-align: center" @endif class="space">
                          @if(Auth::user()->hasRole('User'))
                            <a href="{{ route('showevent',$d->id) }}" class="button" style="width: max-content;"
                          @if(Auth::check() && Auth::user()->hasRole('User') && !checkAttendance(Auth::user()->id,$d->id)) rowspan="2" @endif>Apskatīt Pasākumu</a>
                          </td>
                          @else
                          <a href="{{ route('showevent',$d->id) }}" class="button ">Apskatīt</a>
                          </td>
                          <td class="sliderbutton space @if(date("Y-m-d") <= $d->Datefrom) notactivereport @endif" ><a href="{{ route('downloadreport',$d->id) }}" class="button @if(date("Y-m-d") <= $d->Datefrom) reporttooltip @endif">Atskaite</a></td>
                                <div class="questiontooltip"></div>
                          @endif
                          @endif
                        </tr>
                      </tbody>
                      @endif
                      @endforeach
                @endif
              </table>
              </div>
              @endfor
          </div>
        </div>
        <span id="countVIP" style="display:none">{{$count}}</span>
        <span id="mainslidermonth"style="display:none"></span>
        <span id="historyslidermonth"style="display:none"></span>

        <ul class="slider-months"> 
          @for($i = 0;$i <= $pages;$i++)
          <li class="slider-months_item">
            <a href="" class="button" id="month{{ $i }}" data-slide-index="{{ $i }}"></a>
          </li>
          @endfor
        </ul>
        <ul class="history-slider-months"> {{-- mēneši kurus apstrādā javascript --}}
            @for($i = -$pages;$i <= 0;$i++)
            <li class="history-slider-months_item">
              <a href="" class="button" id="h-month{{ $i }}" data-slide-index="{{ $i + 5 }}"></a>
            </li>
            @endfor
          </ul>
<br>
        
@endsection