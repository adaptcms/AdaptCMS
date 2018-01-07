@extends('layouts.admin')

@section('content')
    <h1>Add Setting</h1>

    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

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