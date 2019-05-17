@extends('welcome')
@section('content')

<div id="notfound">
	<div class="notfound">
		<div class="notfound-404">
			<h1>404</h1>
		</div>
		<h2>Oops! Lapa nav atrasta!</h2>
		<h3>Izskatās,ka šī lapa vairāk neeksistē vai adrese tika mainīta.</h3>
		<form class="notfound-search">
			<input type="text" placeholder="Meklēt...">
			<button type="button">Meklēt</button>
		</form>
		<a href="/"><span class="arrow"></span>Atgriezties uz galveno lapu</a>
	</div>
</div>

@endsection