<h3>Posts</h3>

@if (empty($data['collection']))
    <p>No posts</p>
@else
    <div class="ui feed">
      @foreach($data['collection'] as $row)
        <div class="event">
          @if(!empty($row['user']['profile_image']))
            <div class="label">
              <img src="{{ $row['user']['profile_image'] }}">
            </div>
          @endif
          <div class="content">
            <div class="summary">
              <a href="{{ $row['user']['url'] }}" class="user">
                {{ $row['user']['username'] }}
              </a> posted a thread
              <div class="date">
                {{ Core::getDateAgo($row['created_at']) }}
              </div>
            </div>
            <div class="meta">
              <a href="{{ $row['url'] }}" class="like">
                {{ $row['name'] }}
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
@endif