@extends('layouts.engine')

@section('content')
	{{ Form::model($model, [ 'class' => 'ui form', 'files' => true ]) }}

		<h1>Edit File</h1>

		<div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>

		<div class="field">
			{{ Form::label('file') }}
			{{ Form::file('file') }}
		</div>

		<div class="field">
				<a href="/uploads{{ $model->path }}" target="_blank" class="ui right labeled icon button green">
					View Current File
					<i class="file icon"></i>
				</a>
		</div>

		<div class="field">
			{{ Form::label('albums[]', 'Albums') }}
			{{ Form::select('albums[]', $albums, $model->getRelatedVal(), [ 'class' => 'ui dropdown', 'multiple' ]) }}
		</div>

		{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

	{{ Form::close() }}
@stop
