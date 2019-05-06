@extends('welcome')
@section('content')

<ul>
    @for($i = 0; $i < count($forminfo); $i++)
    <li>{{$forminfo[$i]}}</li>
    @endfor
</ul>

@endsection