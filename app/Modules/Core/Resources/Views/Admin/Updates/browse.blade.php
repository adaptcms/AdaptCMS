@extends('layouts.engine')

@section('content')
	<h1>AdaptCMS Marketplace</h1>

	@if(!empty($module_type))
		<h2>{{ ucfirst($module_type) }}</h2>
	@endif

	<div class="ui center aligned red segment">
		<form class="ui form">
			<div class="ui huge fluid {{ !empty($module_type) ? $module_type : 'modules' }} search">
			  <div class="ui field ten wide icon input centered">
				<input class="prompt" type="text" placeholder="Search...">
				<i class="search icon"></i>
			  </div>
			  <div class="results"></div>
			</div>
		</form>
	</div>

	<div class="ui two stackable cards">
		@foreach($modules as $module)
			@include('core::Partials/module_box', compact('module'))
		@endforeach
	</div>
@stop
