@extends('welcome')
@section('PageTitle','Meklēt')
@section('content')
<div class="container">
    @if(session()->has('message'))
      <br>
      <div class="alert alert-dismissible alert-success" style="margin-top: 20px;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <p>{{ session()->get('message') }}</p>
      </div>
    @endif
  <div class="content">
    <form action="{{ route('searchget') }}" method="POST">
        {{csrf_field()}}
      <div class="form-group myresrvsearch">
          <label class="col-form-label" for="inputDefault">
              <h1>Meklēt </h1>
              <i>Lai meklēt pasākumu jeb rezervāciju ir jānospiež noteikta poga.</i><br>
              <i>Meklēšanas teksta garums ir no 3 līz 50 simboliem.</i><br>
              <button type="button"  class="btn btn-primary search @if($searchtype == 'checkevent') active @endif" id="eventsearch">Pasākumu</button>
              <input type="hidden" name="eventsearch" value="checkevent">
              @if(Auth::check())
              <button type="button" class="btn btn-primary search @if($searchtype == 'checkreservation') active @endif" id="reservsearch">Rezervāciju</button>
              <input type="hidden" name="reservatesearch" value="">
              @endif
        </label>
        <div class="col-lg-12">
        <input type="text" name="search" class="form-control searchinputpage {{ $errors->has('search') ? ' is-invalid' : '' }}" placeholder="Meklēt..." id="mysearchinput" value="{{ $searchtext }}">
          <button type="submit" class="btn btn-primary searchsubmit searchsubmitpage">Meklēt</button>
          @if ($errors->has('search'))
            <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('search') }}</strong>
            </span>
        @endif
        </div>
      </div>
          <div class="mysearchpagediv" id="eventOptions">
              <h3>Meklēt pasākumu:</h3> 
              <div class="custom-control custom-checkbox mysearchpagecb">
                    <input type="hidden" name="eventtitle" value="off" />
                    <input type="checkbox" name="eventtitle" class="custom-control-input" id="customCheck1" @if($checkbox[0] == 'on' && $searchtype == 'checkevent') checked="" @endif>
                    <label class="custom-control-label" for="customCheck1">Pēc nosaukuma</label>
                  </div>
                  <div class="custom-control custom-checkbox mysearchpagecb">
                      <input type="hidden" name="eventdate" value="off" />
                    <input type="checkbox" name="eventdate" class="custom-control-input" id="customCheck2" @if($checkbox[1] == 'on' && $searchtype == 'checkevent') checked="" @endif>
                    <label class="custom-control-label" for="customCheck2">Pēc datuma</label>
                  </div>
                  <div class="custom-control custom-checkbox mysearchpagecb">
                      <input type="hidden" name="eventaddress" value="off" />
                    <input type="checkbox" name="eventaddress" class="custom-control-input" id="customCheck3" @if($checkbox[2] == 'on' && $searchtype == 'checkevent') checked="" @endif>
                    <label class="custom-control-label" for="customCheck3">Pēc adreses</label>
                  </div>
                  <div class="custom-control custom-checkbox mysearchpagecb">
                      <input type="hidden" name="eventanotation" value="off" />
                      <input type="checkbox" name="eventanotation" class="custom-control-input" id="customCheck4" @if($checkbox[3] == 'on' && $searchtype == 'checkevent') checked="" @endif>
                      <label class="custom-control-label" for="customCheck4">Pēc anotācijas</label>
                  </div>
            </div>
        <div class="mysearchpagediv" id="reservationsOptions" style="display:none">
                <h3>Meklēt Rezervāciju:</h3>
            <div class="custom-control custom-checkbox mysearchpagecb">
                <input type="hidden" name="reservemail" value="off" />
                    <input type="checkbox" name="reservemail" class="custom-control-input" id="customCheck5" @if($checkbox[0] == 'on' && $searchtype == 'checkreservation') checked="" @endif>
                    <label class="custom-control-label" for="customCheck5">Pēc e-pasta</label>
                  </div>
                  <div class="custom-control custom-checkbox mysearchpagecb">
                      <input type="hidden" name="reservtickets" value="off" />
                    <input type="checkbox" name="reservtickets" class="custom-control-input" id="customCheck6" @if($checkbox[1] == 'on' && $searchtype == 'checkreservation') checked="" @endif>
                    <label class="custom-control-label" for="customCheck6">Pēc biļešu skaita</label>
                  </div>
                  <div class="custom-control custom-checkbox mysearchpagecb" style="width: 224px;">
                    <input type="hidden" name="reserveventtitle" value="off" />
                    <input type="checkbox" name="reserveventtitle" class="custom-control-input" id="customCheck7" @if($checkbox[2] == 'on' && $searchtype == 'checkreservation') checked="" @endif>
                    <label class="custom-control-label" for="customCheck7">Pēc pasākuma nosaukuma</label>
                  </div>
                  <div class="custom-control custom-checkbox mysearchpagecb">
                      <input type="hidden" name="resertransport" value="off" />
                      <input type="checkbox" name="resertransport" class="custom-control-input" id="customCheck8" @if($checkbox[3] == 'on' && $searchtype == 'checkreservation') checked="" @endif>
                      <label class="custom-control-label" for="customCheck8">Pēc transporta</label>
                  </div>
          </div>
    </form>
    <div class="contain" style="width: 70%"> 
      <table class="eventtable"> {{-- Ja lietotājs lietotājs ir viesis jeb parasts lietotājs un pasākumu kuri nav melnraksti nav izvadī ziņu --}}
        @if(Auth::check() && Auth::user()->hasRole('User') && $data->where('Melnraksts',0)->count() == 0 || !Auth::check() && $data->where('Melnraksts',0)->count() == 0)
          <h3><i style="margin-left: 10%;">Nav atrastu rezultātu</i></h3> {{-- ja lietotājs ir admins un pasākumu vispār nav,tad izvadīt ziņu --}}
        @elseif(Auth::check() && Auth::user()->hasRole('Admin') && $data->count() == 0)
          <h3><i style="margin-left: 10%;">Nav atrastu rezultātu</i></h3>
        @else
            @if($type == 'event')
          <thead>
            <tr>
              <th scope="col" class="content">Datums</th>
              <th class="space" scope="col">Pasākums</th>
              <th class="space" scope="col"></th>
            </tr>
          </thead>
          
          @foreach ($data as $d){{-- līdzīgi kā slierī izvada pasākumus (home.blade.php) --}}
          @if($d->Melnraksts == 0 || Auth::check() && Auth::user()->hasRole('Admin')) {{-- ja pasākums nav melnrakst jeb ja sarakstu pārskata admins,tad tikai rādīt šo ierakstu --}}
            <tbody class="searchcontent">
              <tr>
                <td class="top"><a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                  <div class="eventdate">
                      <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($d->Datefrom) }}</span></div>
                      <div class="eventmonth block h-center v-center"><span class="pagmonth{{ $counter }}">Mēnesis</span></div>
                    </div>
                </td>
                <td class="top space eventinfo"><a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                  <h5 class="eventtitle">{{ $d->Title }}@if($d->Melnraksts == 1) (Melnraksts) @endif</h5> {{-- tā kā šim būs piekļuve tiaki adminam var pārbaudī tikai uz melnrakstu --}}
                  <p>Kad: <span class="searcheventdate">{{ geteventdate($d->Datefrom) }}</span></p><span class="searcheventdate" id='eventdate{{ $counter++ }}'style="display:none">{{ $d->Datefrom }}</span>
                  <p>Kur: <span class="eventaddress">{{ $d->Address }}</span></p>
                  <i class="searchanotation">{{ $d->Anotation }}</i>
                </td>
                <td class="space">
                @if(date("Y-m-d") < $d->Datefrom && Auth::check() && Auth::user()->hasRole('Admin') && checkAuthor(Auth::user()->email,$d->id))
                <a href="{{ route('showedit',$d->id) }}" class="button">Rediģēt</a>
                @else
                <a href="{{ route('showevent',$d->id) }}" class="button">Apskatīt</a>
                @endif
            </td>
              </tr>
            </tbody>
            @endif
          @endforeach
          @elseif($type == 'reservation')
          <thead>
                <tr>
                  <th scope="col" class="content">Bilde</th>
                  <th class="space" scope="col">Vārds</th>
                  <th class="space" scope="col">Pasākuma nosaukums</th>
                  <th class="space" scope="col">Transporta veids</th>
                  <th></th>
                </tr>
              </thead>
              @foreach ($data as $d){{-- līdzīgi kā slierī izvada datus (home.blade.php) --}}
              @if(Auth::check() && Auth::user()->hasRole('User') && $d->user_id != Auth::user()->id)
              @else
                <tbody>
                  <tr>
                    <td class="top">
                      @if(Storage::disk('avatar')->has(getuserbyid($d->user_id)->Avatar))
                      <a href="/profile-avatar/{{getuserbyid($d->user_id)->Avatar}}">
                        <img src="/profile-avatar/{{getuserbyid($d->user_id)->Avatar}}"  width="75" height="75">
                      </a>
                      @else
                      <a href="/png/Empty-Avatar.png">
                        <img src="/png/Empty-Avatar.png" width="75" height="75">
                      </a>
                      @endif
                    </td>
                    <td class="top space eventinfo">
                      <a class='divlink' href="{{ route('showreservation',$d->id) }}"></a>
                      <h5>{{ getuserbyid($d->user_id)->First_name }} {{ getuserbyid($d->user_id)->Last_name }}</h5>
                      <i>Biļešu skaits: {{ $d->Tickets }}</i>
                    </td>
                    <td class="top space eventinfo">
                        <a class='divlink' href="{{ route('showreservation',$d->id) }}"></a>
                        <h4>{{ geteventbyid($d->EventID)->Title }}</h4>
                    </td>
                    <td class="top space eventinfo">
                        <a class='divlink' href="{{ route('showreservation',$d->id) }}"></a>
                    <h4>{{ $d->Transport }}</h4>
                    </td>
                    <td class="space"><a href="{{ route('showreservation',$d->id) }}" class="button reservsmall">Apskatīt</a></td>
                  </tr>
                </tbody>
                @endif
              @endforeach
          @endif
        @endif
      </table>
    </div> {{-- paginācijas linki --}}
        <span style="display:none" id="counter">{{ $counter }}</span>
        @if ($data->count() > 0)
          <ul class="slider-months">
            @for ($i = 0; $i < count($pagenumber); $i++)
              <li class="slider-months_item">
                <a href="{{ substr_replace(url()->current(),$i + 1,-1) }}" 
                class="button">{{ $i + 1 }}</a>
              </li>
            @endfor
          </ul>
          <br>
        @endif
  </div>
</div>
@endsection