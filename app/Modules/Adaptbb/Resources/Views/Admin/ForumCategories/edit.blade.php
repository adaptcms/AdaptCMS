@extends('layouts.engine')

    @section('content')
		{{ Form::model($item, [ 'class' => 'ui form' ]) }}

			<h1>Edit Category</h1>

			<div class="required field">
				{{ Form::label('name') }}
				{{ Form::text('name') }}
			</div>

			{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

		{{ Form::close() }}
    @stop
