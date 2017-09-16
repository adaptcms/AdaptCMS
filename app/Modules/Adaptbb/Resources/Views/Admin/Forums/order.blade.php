@extends('layouts.admin')

@section('content')
			<h1>Order Forums</h1>

			<ol class="ui items sortable adaptbb-forums">
				@foreach($items as $item)
					<li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
				@endforeach
			</ol>
@stop

@push('js')
    <script src="/assets/modules/adaptbb/js/admin.order.js"></script>
@endpush
