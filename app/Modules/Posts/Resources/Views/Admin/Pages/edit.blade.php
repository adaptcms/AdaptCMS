@extends('layouts.engine')

@section('content')
	{{ Form::model($model, [ 'class' => 'ui form pages']) }}
		<h1>Edit Page</h1>

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
			{{ Form::text('name', $model->name, [ 'v-model' => 'name' ]) }}
		</div>

		<div class="required field">
			{{ Form::label('slug', 'URL Slug') }}
			{{ Form::text('slug', $model->slug, [ 'v-model' => 'slug' ]) }}
		</div>

		<div class="required field">
			{{ Form::label('body') }}

			{!! Form::textarea('body', $model->body, [ 'class' => 'wysiwyg code-view' ]) !!}
		</div>

		<div class="field">
			{{ Form::label('meta_keywords') }}

			<div class="ui fluid multiple search selection dropdown allowAdditions">
				{{ Form::hidden('meta_keywords') }}
				<i class="dropdown icon"></i>
				<div class="default text">Keywords</div>
				<div class="menu"></div>
			</div>
		</div>

		<div class="field">
			{{ Form::label('meta_description') }}
			{{ Form::textarea('meta_description') }}
		</div>

        <div class="inline field">
            <div class="ui toggle checkbox">
				{{ Form::checkbox('status', true, true, [ 'class' => 'hidden' ]) }}
                  {{ Form::label('status') }}
            </div>
        </div>

        {{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

	{{ Form::close() }}
@stop
