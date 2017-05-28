@extends('core::Layouts/install')

@section('content')
    {{ Form::open([ 'class' => 'ui massive form' ]) }}
        <h2>Me</h2>

        <div class="ui two fields">
          <div class="ui field">
              {{ Form::label('webhost', 'Who is your web host?') }}
              {{ Form::text('webhost', '', [ 'placeholder' => 'GoDaddy' ]) }}
          </div>

          <div class="ui field">
            <div class="ui toggle checkbox">
              {{ Form::checkbox('cms_collect_data', true, [ 'class' => 'hidden' ]) }}
              <label>Allow Collecting of non-sensitive data?</label>
            </div>
          </div>
        </div>

        <button type="submit" class="ui right labeled icon huge green button pull-right">
            Next: Account
            <i class="user icon"></i>
        </button>
    {{ Form::close() }}
@stop
