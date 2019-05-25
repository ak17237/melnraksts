@extends('welcome')
@section('PageTitle','Kļūda')
@section('content')

<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>40{{ $state }}</h1>
			</div>
			<h2>Oops! {{ $message[0] }}</h2>
			<h3>{{ $message[1] }}</h3>
			<form action="{{ route('searchget') }}" method="POST" class="notfound-search">
					{{csrf_field()}}
				<input type="text" name="search" placeholder="Meklēt...">
				<button type="submit">Meklēt</button>
			</form>
			<a href="/"><span class="arrow"></span>Atgriezties uz galveno lapu</a>
		</div>
	</div>

@endsection