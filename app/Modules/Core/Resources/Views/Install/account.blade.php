@extends('core::Layouts/install')

@section('content')
    {{ Form::model($user, [ 'class' => 'ui massive form' ]) }}
        <h2>Account</h2>

        <div class="ui two fields">
          <div class="ui field">
              {{ Form::label('username') }}
              {{ Form::text('username') }}
          </div>

          <div class="ui field">
              {{ Form::label('email') }}
              {{ Form::email('email') }}
          </div>
        </div>

        <div class="ui two fields">
          <div class="ui field">
              {{ Form::label('password') }}
              {{ Form::password('password', [ 'value' => '', 'autocomplete' => 'off' ]) }}
          </div>

          <div class="ui field">
              {{ Form::label('password_confirmation') }}
              {{ Form::password('password_confirmation', [ 'value' => '', 'autocomplete' => 'off' ]) }}
          </div>
        </div>

        <div class="ui two fields">
          <div class="ui field">
              {{ Form::label('first_name') }}
              {{ Form::text('first_name') }}
          </div>

          <div class="ui field">
              {{ Form::label('last_name') }}
              {{ Form::text('last_name') }}
          </div>
        </div>

        <button type="submit" class="ui right labeled icon huge green button pull-right">
            Finish!
            <i class="user icon"></i>
        </button>
    {{ Form::close() }}
@stop
