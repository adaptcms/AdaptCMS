@extends('layouts.admin')

@section('content')
	<h1>Order Forums</h1>

	<ul class="ui relaxed items sortable adaptbb-forums">
		@foreach($items as $item)
			<li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
		@endforeach
	</ul>
@stop

@push('js')
    <script src="/assets/modules/adaptbb/js/admin.order.js"></script>
@endpush
