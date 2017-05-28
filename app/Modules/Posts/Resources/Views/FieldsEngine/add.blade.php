@extends('layouts.engine')

@section('content')
	{{ Form::model($model, [ 'class' => 'ui form fields', 'v-cloak' ]) }}

		<h1>Add Field</h1>

		<div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>

		<div class="required field">
			{{ Form::label('name') }}
			{{ Form::text('name', '', [ 'v-model' => 'name', '@change' => 'updateCaption()' ]) }}
		</div>

		<div class="required field">
			{{ Form::label('caption') }}
			{{ Form::text('caption', '', [ 'v-model' => 'caption' ]) }}
		</div>

		<div class="required field">
			{{ Form::label('field_type') }}
			{{ Form::select('field_type', $field_types, null, [ 'v-model' => 'field_type', 'class' => 'dropdown' ]) }}
		</div>

		<div class="required field">
			{{ Form::label('category_id', 'Category') }}
			{{ Form::select('category_id', $categories, null, [ 'class' => 'dropdown' ]) }}
		</div>

		{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

	{{ Form::close() }}
@stop
