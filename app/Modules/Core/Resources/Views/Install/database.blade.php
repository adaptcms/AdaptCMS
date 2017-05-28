@extends('core::Layouts/install')

@section('content')
    {{ Form::open([ 'class' => 'ui massive form' ]) }}
        <h2>Database</h2>

        <div class="ui two fields">
          <div class="ui required field">
              {{ Form::label('DB_CONNECTION', 'Connection Type') }}
              {{ Form::select('DB_CONNECTION', $connection_types, 'mysql', [ 'class' => 'ui dropdown' ]) }}
          </div>

          <div class="ui required field">
              {{ Form::label('DB_HOST', 'Host') }}
              {{ Form::text('DB_HOST', '', [ 'v-model' => 'DB_HOST' ]) }}
          </div>
        </div>

        <div class="ui two fields">
          <div class="ui required field">
              {{ Form::label('DB_PORT', 'Port') }}
              {{ Form::text('DB_PORT', '', [ 'v-model' => 'DB_PORT' ]) }}
          </div>

          <div class="ui required field">
              {{ Form::label('DB_DATABASE', 'Database Name') }}
              {{ Form::text('DB_DATABASE', '', [ 'v-model' => 'DB_DATABASE' ]) }}
          </div>
        </div>

        <div class="ui two fields">
            <div class="ui required field">
                {{ Form::label('DB_USERNAME', 'Username') }}
                {{ Form::text('DB_USERNAME', '', [ 'v-model' => 'DB_USERNAME' ]) }}
            </div>

            <div class="ui required field">
                {{ Form::label('DB_PASSWORD', 'Password') }}
                {{ Form::password('DB_PASSWORD', [ 'value' => '', 'autocomplete' => 'off', 'v-model' => 'DB_PASSWORD' ]) }}
            </div>
        </div>

        <h2>Your Website</h2>

        <div class="ui required field">
            {{ Form::label('sitename', 'Website Name') }}
            {{ Form::text('sitename', '', [ 'v-model' => 'sitename' ]) }}
        </div>

        <a
          href=""
          class="ui right labeled icon huge primary button left-right"
          :class="{ 'disabled': !filled_out }"
          @click.prevent="testConnection()"
        >
            Test Connection
            <i class="share icon"></i>
        </a>

        <button type="submit" class="ui right labeled icon huge green button pull-right" :class="{ 'disabled': !database_connection || !sitename }">
            Next: Me
            <i class="child icon"></i>
        </button>
    {{ Form::close() }}
@stop
