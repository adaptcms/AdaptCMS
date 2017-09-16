@extends('layouts.engine')

    @section('content')
		{{ Form::model($model, [ 'class' => 'ui form' ]) }}
		
			<h2>Edit Album</h2>
		
			<div class="alert alert-danger">
		        <ul>
		            @foreach ($errors as $fields)
		            	@foreach($fields as $error)
		                	<li>{{ $error }}</li>
		                @endforeach
		            @endforeach
		        </ul>
		    </div>
		
			<div class="required field">
				{{ Form::label('name') }}
				{{ Form::text('name') }}
			</div>
			
			{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
		
		{{ Form::close() }}
    @stop