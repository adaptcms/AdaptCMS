@extends('layouts.engine')

    @section('content')
            <h1>Add Settings Category</h1>

        <div class="alert alert-danger">
            <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
            @endforeach
                        </ul>
    </div>

    {{ Form::model($model, [ 'class' => 'ui form' ]) }}

                    <div class="required field">
                    {{ Form::label('name') }}
                            {{ Form::text('name') }}
                    </div>

                    {{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

        {{ Form::close() }}
@stop