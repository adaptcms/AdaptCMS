<section class="hero is-login is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-grey">Login</h3>
          <p class="subtitle has-text-grey">Please login to proceed.</p>
          <div class="box">
            <figure class="avatar">
              <img src="/img/AdaptCMSLogoJPG_2.jpg">
            </figure>
            {!! Form::open() !!}
              <div class="field">
                <div class="control">
                  {{ Form::text('username', Request::get('username'), [ 
                    'class' => 'input is-large',
                    'placeholder' => 'Username'
                  ]) }}
                </div>
              </div>

              <div class="field">
                <div class="control">
                  {{ Form::password('password', [
                    'class' => 'input is-large',
                    'placeholder' => 'Your Password'
                  ]) }}
                </div>
              </div>

              {{ Form::submit('Login', array('class' => 'button is-block is-info is-large is-fullwidth')) }}

              <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            {!! Form::close() !!}
          </div>
          <p class="has-text-grey">
            <a href="{{ route('register') }}">Sign Up</a> &nbsp;·&nbsp;
            <a href="{{ route('password.request') }}">Forgot Password</a>
          </p>
        </div>
      </div>
    </div>
  </section>