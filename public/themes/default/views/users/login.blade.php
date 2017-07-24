<div class="twelve wide mobile eight wide computer column">
  {!! Form::open([ 'class' => 'ui equal width large form' ]) !!}
      <h2 class="ui teal image header">
        <div class="content">
          Login to your account
        </div>
      </h2>
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input full width">
              <i class="user icon"></i>

              {{ Form::text('username', Request::get('username'), [ 'placeholder' => 'Username' ]) }}
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input full width">
              <i class="lock icon"></i>

              {{ Form::password('password', [ 'placeholder' => 'Password' ]) }}
            </div>
          </div>

          {{ Form::submit('Login', array('class' => 'ui fluid large blue submit button')) }}
        </div>

      <div class="ui message">
        New to us? <a href="{{ route('register') }}">Sign Up</a><br />
        <a href="{{ route('password.request') }}">Forgot your password?</a>
      </div>
      
      {!! csrf_field() !!}
  {!! Form::close() !!}
</div>
