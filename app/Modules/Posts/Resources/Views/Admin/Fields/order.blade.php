@extends('layouts.admin')

@section('content')
    <h1>Order Fields</h1>

    <ul class="ui relaxed items sortable fields">
        @foreach($items as $item)
            <li class="item" data-id="{{ $item->id }}">{{ $item->name }}</li>
        @endforeach
    </ul>
@stop