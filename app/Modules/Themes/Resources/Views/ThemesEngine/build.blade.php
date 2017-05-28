@extends('layouts.theme_builder')

@push('css')
	<link href="/css/vendor/jquery.color-picker.min.css" rel="stylesheet">
@endpush

@section('content')
    <div id="app"></div>
@stop

@push('js')
	<script src="/js/vendor/jquery.color-picker.min.js"></script>
    <script src="/apps/theme-builder/dist/build.js"></script>
@endpush