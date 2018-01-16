@extends('layouts.admin')

@section('content')
    <h1>Edit Template</h1>

    {{ Form::open([ 'class' => 'ui form' ]) }}

        <div class="field">
            {{ Form::label('path') }}
            {{ Form::text('path', public_path() . '/themes/' . $path, [ 'disabled' ]) }}
        </div>

        <div class="required field">
            {{ Form::label('body') }}

            {!! Form::textarea('body', '', [ 'class' => 'wysiwyg code-view' ]) !!}
            <div class="api-call" data-url="/admin/api/themes?template_path={{ $path }}"></div>
        </div>

        {{ Form::button('Save', [ 'class' => 'ui button primary submit' ]) }}

    {{ Form::close() }}
@stop
