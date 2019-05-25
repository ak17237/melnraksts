@extends('welcome')
@section('PageTitle','Kļūda')
@section('content')

<div id="notfound">
	<div class="notfound">
		<div class="notfound-404">
			<h1>404</h1>
		</div>
		<h2>Oops! Lapa nav atrasta!</h2>
		<h3>Izskatās,ka šī lapa vairāk neeksistē vai adrese tika mainīta.</h3>
		<form action="{{ route('searchget') }}" method="POST" class="notfound-search">
				{{csrf_field()}}
			<input type="text" name="search" placeholder="Meklēt...">
			<button type="submit">Meklēt</button>
		</form>
		<a href="/"><span class="arrow"></span>Atgriezties uz galveno lapu</a>
	</div>
</div>

@endsection