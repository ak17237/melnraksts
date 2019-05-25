@extends('welcome')
@section('PageTitle','Melnraksti')
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
      @if ($data->count() > 0)
      <div class="form-group myresrvsearch" style="padding-top: 20px;">
          <label class="col-form-label" for="inputDefault">Meklēt pasākumu</label>
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
              <input type="checkbox" class="custom-control-input" id="customCheck5" checked="">
              <label class="custom-control-label" for="customCheck5">Pēc anotācijas</label>
          </div>
        </div>
        @endif
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
            <tbody class="searchcontent">
              <tr>
                <td class="top clickshow"><a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                  <div class="eventdate">
                      <div class="eventday block h-center v-center"><span class="daystyle">{{ geteventday($d->Datefrom) }}</span></div>
                      <div class="eventmonth block h-center v-center"><span class="pagmonth{{ $counter }}">Mēnesis</span></div>
                    </div>
                </td>
                <td class="top space eventinfo clickshow"><a class='divlink' href="{{ route('showevent',$d->id) }}"></a>
                  <h5 class="eventtitle">{{ $d->Title }}</h5>
                  <p>Kad: <span class="searcheventdate">{{ geteventdate($d->Datefrom) }}</span></p><span class="searcheventdate" id='eventdate{{ $counter++ }}'style="display:none">{{ $d->Datefrom }}</span>
                  <p>Kur: <span class="eventaddress">{{ $d->Address }}</span></p>
                  <i class="searchanotation">{{ $d->Anotation }}</i>
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