@extends('layouts.admin')

@section('content')
    {{ Form::model($model, [ 'class' => 'ui form' ]) }}
    
        <h1>Edit Role</h1>
    
        @if(!empty($errors))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <div class="required field">
            {{ Form::label('name') }}
            {{ Form::text('name') }}
        </div>

        <div class="required field">
            {{ Form::label('redirect_route_name') }}
            {{ Form::text('redirect_route_name') }}
        </div>
        
        <div class="required field">
            {{ Form::label('role', 'Inherit from Role(s)') }}
            {{ Form::select('role', $roles, null, [ 
                'placeholder' => 'Pick a Role',
                'class' => 'ui dropdown',
                'multiple'
            ]) }}
        </div>
        
        {{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}
    
    {{ Form::close() }}
@stop