@extends('layouts.admin')

@section('content')
	<h1>Order Pages</h1>

	<ul class="ui relaxed items sortable pages">
		@foreach($items as $item)
			<li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
		@endforeach
	</ul>
@stop
