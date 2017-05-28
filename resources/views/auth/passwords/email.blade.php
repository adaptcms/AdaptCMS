@extends('layouts.simple')

@section('content')
    <div class="twelve wide mobile eight wide computer column">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

      {{ Form::open(array('route' => 'password.email', 'class' => 'ui equal width large form')) }}
          {{ csrf_field() }}

          <h2 class="ui teal image header">
            <div class="content">
              Reset Password
            </div>
          </h2>
            <div class="ui stacked segment">
              <div class="required field">
                <div class="ui left icon input full width">
                  <i class="mail icon"></i>
                  {{ Form::email('email', old('email'), [ 'placeholder' => 'E-Mail Address...', 'autofocus', 'required' ]) }}
                </div>
              </div>

              {{ Form::submit('Send Reset Link', array('class' => 'ui fluid large blue submit button')) }}
            </div>
      {{ Form::close() }}
    </div>
@endsection
