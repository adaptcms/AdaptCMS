@if(Auth::check() && Auth::user()->role && Auth::user()->role->level > 1)
    <!-- Following Menu -->
    <div class="ui large top fixed hidden menu admin-menu inverted">
      <div class="ui container">
        <div class="right menu">
            <div class="item">
                <a href="{{ route('admin.dashboard') }}" class="ui primary button">Back To Admin</a>
            </div>
        </div>
      </div>
    </div>
@endif