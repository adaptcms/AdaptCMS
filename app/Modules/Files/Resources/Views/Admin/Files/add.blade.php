@extends('layouts.admin')

    @section('content')
		{{ Form::model($model, [ 'class' => 'ui form', 'files' => true ]) }}
		
			<h1>Upload File</h1>
			
			<div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		
			<div class="required field">
				{{ Form::label('file') }}
				{{ Form::file('file') }}
			</div>

			{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
		
		{{ Form::close() }}
    @stop