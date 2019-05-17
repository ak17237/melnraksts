@extends('welcome')
@section('content')

<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>40{{ $state }}</h1>
			</div>
			<h2>Oops! {{ $message[0] }}</h2>
			<h3>{{ $message[1] }}</h3>
			<form class="notfound-search">
				<input type="text" placeholder="Meklēt...">
				<button type="button">Meklēt</button>
			</form>
			<a href="/"><span class="arrow"></span>Atgriezties uz galveno lapu</a>
		</div>
	</div>

@endsection