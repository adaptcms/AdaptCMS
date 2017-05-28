@extends('layouts.engine')

@section('content')
			<h1>Order Pages</h1>

			<ol class="ui items sortable pages">
				@foreach($items as $item)
					<li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
				@endforeach
			</ol>
@stop
