<div class="ui centered grid">
	<div class="ui three columns centered grid">
		{{ Form::open(array('route' => 'register', 'class' => 'ui form')) }}
		    <h1>Register</h1>

		    <div class="required field">
		    {{ Form::label('username', 'Username') }}
		        {{ Form::text('username', Request::get('username')) }}
		    </div>

		    <div class="required field">
		        {{ Form::label('password', 'Password') }}
		        {{ Form::password('password') }}
		    </div>

		    <div class="required field">
		        {{ Form::label('email', 'Email Address') }}
		        {{ Form::text('email', Request::get('email')) }}
		    </div>

		    <div class="required field">
		        {{ Form::label('first_name', 'First Name') }}
		        {{ Form::text('first_name', Request::get('first_name')) }}
		    </div>

		    <div class="required field">
		        {{ Form::label('last_name', 'Last Name') }}
		        {{ Form::text('last_name', Request::get('last_name')) }}
		    </div>

		    {{ Form::submit('Register', array('class' => 'ui button primary')) }}

		     <div class="ui buttons">
		        <a href="{{ route('login') }}" class="ui button green tiny">Login</a>

		        <a href="{{ route('password.request') }}" class="ui button red tiny">Forgot Password</a>
		     </div>

		    {{ Form::hidden(env('APP_KEY') . '_token', '') }}
		{{ Form::close() }}
	</div>
</div>
