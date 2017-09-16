@extends('layouts.admin')

    @section('content')
		{{ Form::model($model, [ 'class' => 'ui form' ]) }}

			<h1>Edit User</h1>

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
				{{ Form::text('username', $model->username, [ 'autocomplete' => 'off' ]) }}
			</div>

			<div class="required field">
				{{ Form::label('email') }}
				{{ Form::email('email', $model->email, [ 'autocomplete' => 'off' ]) }}
			</div>

			<div class="field">
				{{ Form::label('password') }}
				{{ Form::password('password', [ 'value' => '', 'autocomplete' => 'off' ]) }}
			</div>

			<div class="field">
				{{ Form::label('password_confirmation') }}
				{{ Form::password('password_confirmation', [ 'value' => '', 'autocomplete' => 'off' ]) }}
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
				{{ Form::select('roles[]', $roles, $model->roles->pluck('name'), [ 
					'class' => 'ui dropdown',
					'multiple'
				]) }}
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
