@extends('layouts.admin')

@section('content')
	{{ Form::model($model, [ 'class' => 'ui form fields' ]) }}
	
		<h1>Edit Field</h1>
		
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
			{{ Form::text('name', $model->name, [ 'v-model' => 'name', '@change' => 'updateCaption()' ]) }}
		</div>
		
		<div class="required field">
			{{ Form::label('caption') }}
			{{ Form::text('caption', $model->caption, [ 'v-model' => 'caption' ]) }}
		</div>
		
		<div class="required field">
			{{ Form::label('field_type') }}
			{{ Form::select('field_type', $field_types, $model->field_type, [ 'class' => 'dropdown' ]) }}
		</div>
		
		<div class="required field">
			{{ Form::label('category_id', 'Category') }}
			{{ Form::select('category_id', $categories, $model->category_id, [ 'class' => 'dropdown' ]) }}
		</div>
		
		{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
	
	{{ Form::close() }}
@stop