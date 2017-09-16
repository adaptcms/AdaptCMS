@extends('layouts.engine')

    @section('content')
		{{ Form::model($model, [ 'class' => 'ui form' ]) }}
		
			<h1>Add Album</h1>
		
			<div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
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