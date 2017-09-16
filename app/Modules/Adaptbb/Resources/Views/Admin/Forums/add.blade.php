@extends('layouts.admin')

    @section('content')
		{{ Form::model($item, [ 'class' => 'ui form' ]) }}

			<h1>Add Forum</h1>

			<div class="required field">
				{{ Form::label('name') }}
				{{ Form::text('name') }}
			</div>

      <div class="required field">
				{{ Form::label('description') }}
				{{ Form::textarea('description') }}
			</div>

      <div class="field">
				{{ Form::label('category_id', 'Category') }}
				{{ Form::select('category_id', $categories, $item->category_id, [ 'class' => 'ui dropdown' ]) }}
			</div>

			{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

		{{ Form::close() }}
    @stop
