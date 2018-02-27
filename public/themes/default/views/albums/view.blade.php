<div class="level">
  <div class="level-left">
    <div class="level-item">
      <h1 class="title is-1">Album</h1>
    </div>
    <div class="level-item">
      <h2 class="subtitle is-4">{{ $album->name }}</h2>
    </div>
  </div>

  <div class="level-right">
    <div class="level-item">
      <a href="{{ route('albums.index') }}" class="button primary">
        <span class="icon">
          <i class="fa fa-hand-o-left"></i>
        </span>
        <span>Back to Albums</span>
      </a>
    </div>
  </div>
</div>

@if(empty($files))
    <p>
      No files currently added.
    </p>
@else
  <div class="columns features">
    @foreach($files as $file)
      <div class="column is-4">
        <div class="card">
          <div class="card-image">
            <img src="/uploads/{{ $file->file->path }}">
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{ $files->links() }}
@endif
