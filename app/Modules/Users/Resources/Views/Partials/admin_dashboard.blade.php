<h3>Users</h3>

@if (empty($data['collection']))
    <p>No posts</p>
@else
    <div class="ui feed">
      @foreach($data['collection'] as $row)
        <div class="event">
          @if(!empty($row['profile_image']))
            <div class="label">
              <img src="{{ $row['profile_image'] }}">
            </div>
          @endif
          <div class="content">
            <div class="summary">
              <a href="{{ $row['url'] }}" class="user">
                {{ $row['username'] }}
              </a> signed up
              <div class="date">
                {{ Core::getDateAgo($row['created_at']) }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
@endif