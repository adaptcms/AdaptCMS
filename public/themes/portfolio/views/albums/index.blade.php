    <h1>Albums</h1>

    @if(empty($albums))
        <p>
          No albums currently created.
        </p>
    @else
        <div class="ui special cards">
          @foreach($albums as $album)
          <div class="ui card">
            <div class="blurring dimmable image">
              <div class="ui dimmer">
                <div class="content">
                  <div class="center">
                    <a href="{{ route('albums.view', [ 'slug' => $album->slug ]) }}" class="ui right labeled icon inverted button">
                      View Gallery
                      <i class="picture icon"></i>
                    </a>
                  </div>
                </div>
              </div>
              @if($album->albumFiles->count())
                <img src="/uploads/{{ $album->getNewestFile()->path }}">
              @endif
            </div>
            <div class="content">
              <a href="{{ route('albums.view', [ 'slug' => $album->slug ]) }}" class="header">
                {{ $album->name }}
              </a>
            </div>
            <div class="extra content">
              <a>
                <i class="file icon"></i>
                {{ $album->albumFiles->count() }} File(s)
              </a>
            </div>
          </div>
          @endforeach
        </div>
        {{ $albums->links() }}

        <div class="clear"></div>
    @endif
