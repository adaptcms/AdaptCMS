@extends('layouts.admin')

@section('content')
    <h1>Order Categories</h1>

    <ul class="ui relaxed items sortable categories">
        @foreach($items as $item)
            <li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
        @endforeach
    </ul>
@stop