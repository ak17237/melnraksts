@extends('welcome')
@section('content')

<div id="notfound">
	<div class="notfound">
		<div class="notfound-404">
			<h3>Oops! {{ $message[0] }}</h3>
			<h1><span>4</span><span>0</span><span>{{ $state }}</span></h1>
        </div>
        <h2>{{ $message[1] }}</h2>
        <h3>Atgriezties uz galveno lapu <a href="/">Home</a></h3>
	</div>
</div>

@endsection