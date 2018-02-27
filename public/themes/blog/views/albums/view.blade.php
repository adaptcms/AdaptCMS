  <div class="pull-left">
      <h1>Album</h1>
      <h2>{{ $album->name }}</h2>
  </div>

  <a href="{{ route('albums.index') }}" class="ui right labeled icon primary button pull-right">
      Back to List
      <i class="list icon"></i>
  </a>
  <div class="clear"></div>

  @if(empty($files))
      <p>
        No files currently added.
      </p>
  @else
      <div class="ui small images">
        @foreach($files as $file)
            <a href="/uploads/{{ $file->file->path }}" class="ui image" target="_blank">
              <img src="/uploads/{{ $file->file->path }}" />
            </a>
        @endforeach
      </div>

      {{ $files->links() }}
  @endif
