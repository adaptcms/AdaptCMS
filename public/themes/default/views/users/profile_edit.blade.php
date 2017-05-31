<div class="ui red very padded segment">
	<div class="ten columns centered">
		<h1>Edit Profile</h1>

		@if (!empty($errors))
			<div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif

		{{ Form::model($model, [ 'class' => 'ui huge form', 'files' => true ]) }}
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

				<div class="field">
					<button type="submit" class="ui right labeled icon button primary pull-right">
						Save
						<i class="save icon"></i>
					</button>
				</div>

			{{ Form::close() }}
		</div>
</div>
