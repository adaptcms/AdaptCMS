@extends('layouts.admin')

@section('content')
    <h1>Install Plugin</h1>
    <h2>{{ $module->name }}</h2>

    {{ Form::model($model, [ 'class' => 'ui form' ]) }}

        <div class="required field">
        {{ Form::label('key') }}
                {{ Form::text('key') }}
        </div>
        <div class="required field">
        {{ Form::label('value') }}
        {{ Form::text('value') }}
                    </div>

        <div class="field">
        {{ Form::label('category_id', 'Category') }}
            {{ Form::select('category_id', $categories, '', [ 'class' => 'ui dropdown' ]) }}
        </div>

        {{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

    {{ Form::close() }}
@stop
