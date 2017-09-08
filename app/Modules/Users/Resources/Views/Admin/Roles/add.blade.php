@extends('layouts.engine')

@section('content')
	{{ Form::model($model, [ 'class' => 'ui form' ]) }}
	
		<h1>Add Role</h1>
	
		@if(!empty($errors))
			<div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
	
		<div class="required field">
			{{ Form::label('name') }}
			{{ Form::text('name') }}
		</div>
		
		<div class="required field">
			{{ Form::label('level') }}
			{{ Form::email('level') }}
		</div>
		
		{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
	
	{{ Form::close() }}
@stop