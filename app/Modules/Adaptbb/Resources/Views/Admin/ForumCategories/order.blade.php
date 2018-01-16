@extends('layouts.admin')

@section('content')
    <h1>Order Categories</h1>

    <ul class="ui relaxed items sortable adaptbb-forum-categories">
        @foreach($items as $item)
            <li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
        @endforeach
    </ul>
@stop

@push('js')
    <script src="/assets/modules/adaptbb/js/admin.order.js"></script>
@endpush