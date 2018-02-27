@extends('layouts.admin')

@section('content')
    {{ Form::model($model, [ 'class' => 'ui form' ]) }}
    
        <h1>Edit Theme</h1>
        
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors as $fields)
                    @foreach($fields as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    
        <div class="required field">
            {{ Form::label('name') }}
            {{ Form::text('name') }}
        </div>
        
        <div class="inline field">
            <div class="ui toggle checkbox">
                {{ Form::checkbox('status', true, true) }}
                {{ Form::label('status') }}
            </div>
        </div>
        
        {{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
    
    {{ Form::close() }}
@stop