@extends('layouts.admin')

@section('content')
      <div class="ui massive tall positive message">
        <i class="close icon"></i>
          <div class="header">
            AdaptCMS has been upgraded
          </div>

          <h2>{{ $current_version['branch_name'] }}</h2>

          @if(!empty($current_version['release_notes']))
              <h3>Release Notes</h3>
              {!! $current_version['release_notes'] !!}
          @endif

          @if(!empty($current_version['upgrade_notes']))
              <h3>Upgrade Notes</h3>

              {!! $current_version['upgrade_notes'] !!}
          @endif
      </div>

      <a href="{{ route('admin.dashboard') }}" class="ui right labeled icon primary large button pull-right">
          Back to Dashboard
          <i class="home icon"></i>
      </a>
@stop
