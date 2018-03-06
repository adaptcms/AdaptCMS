@extends('layouts.admin')

@section('content')
    @foreach($data as $row)
        @include($row['viewPath'], [ 'data' => $row ])
    @endforeach
@stop