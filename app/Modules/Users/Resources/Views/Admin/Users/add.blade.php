@extends('layouts.engine')

@section('content')
	{{ Form::model($model, [ 'class' => 'ui form' ]) }}
	
		<h1>Add User</h1>
	
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
			{{ Form::label('username') }}
			{{ Form::text('username') }}
		</div>
		
		<div class="required field">
			{{ Form::label('email') }}
			{{ Form::email('email') }}
		</div>
		
		<div class="required field">
			{{ Form::label('password') }}
			{{ Form::password('password', [ 'autocomplete' => 'off' ]) }}
		</div>
		
		<div class="required field">
			{{ Form::label('password_confirmation') }}
			{{ Form::password('password_confirmation', [ 'autocomplete' => 'off' ]) }}
		</div>
		
		<div class="required field">
			{{ Form::label('first_name') }}
			{{ Form::text('first_name') }}
		</div>
		
		<div class="required field">
			{{ Form::label('last_name') }}
			{{ Form::text('last_name') }}
		</div>
		
		<div class="required field">
			{{ Form::label('roles[]', 'Role') }}
			{{ Form::select('roles[]', $roles, null, [ 
				'class' => 'ui dropdown', 
				'multiple' 
			]) }}
		</div>
		
		{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
	
	{{ Form::close() }}
@stop