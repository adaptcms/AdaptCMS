@extends('layouts.admin')

@section('content')
			<h1>Order Categories</h1>

			<ol class="ui items sortable categories">
				@foreach($items as $item)
					<li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
				@endforeach
			</ol>
@stop
