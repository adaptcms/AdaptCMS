<h1 class="title is-1">Albums</h1>

@if(empty($albums))
    <p>
      No albums currently created.
    </p>
@else
  <div class="columns features">
    @foreach($albums as $album)
      <div class="column is-4">
        <div class="card">
          @if($album->albumFiles->count())
            <div class="card-image has-text-centered">
              <a href="{{ route('albums.view', [ 'slug' => $album->slug ]) }}">
                <img src="/uploads/{{ $album->getNewestFile()->path }}">
              </a>
            </div>
          @endif
          <div class="card-content">
            <a href="{{ route('albums.view', [ 'slug' => $album->slug ]) }}" class="title is-4">
              {{ $album->name }}
            </a>

            <br />

            <span>
              {{ $album->albumFiles->count() }} File(s)
            </span>
          </div>
          <footer class="card-footer">
            <a href="{{ route('albums.view', [ 'slug' => $album->slug ]) }}" class="card-footer-item">
              View Gallery
            </a>
          </footer>
        </div>
      </div>
    @endforeach
  </div>

  {{ $albums->links() }}
@endif