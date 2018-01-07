@foreach(Core::getData('pages', 'all', [], [], 3) as $page)
    @if($page->slug == 'home')
        <a href="{{ route('home') }}" class="{{ Request::url() == route('home') ? 'is-active' : '' }} navbar-item">
        {{ $page->name }}
    </a>
    @else
        <a href="{{ route('pages.view', [ 'slug' => $page->slug ]) }}" class="{{ Request::url() == route('pages.view', [ 'slug' => $page->slug ]) ? 'is-active' : '' }} navbar-item">
            {{ $page->name }}
        </a>
    @endif
@endforeach

@foreach(Core::getData('categories', 'all', [], [ 'ord', 'asc' ], 3) as $category)
    <a href="{{ route('categories.view', [ 'slug' => $category->slug ]) }}" class="{{ Request::url() == route('categories.view', [ 'slug' => $category->slug ]) ? 'is-active' : '' }} navbar-item">
        {{ $category->name }}
    </a>
@endforeach

<a href="{{ route('albums.index') }}" class="{{ Request::url() == route('albums.index') ? 'is-active' : '' }} navbar-item">
Albums
</a>

@if(Module::exists('adaptbb') && Module::isEnabled('adaptbb'))
  <a href="{{ route('plugin.adaptbb.forums.index') }}" class="{{ Request::url() == route('plugin.adaptbb.forums.index') ? 'is-active' : '' }} navbar-item">
  Community
  </a>
@endif

<div class="navbar-item has-dropdown is-hoverable">
  <span class="navbar-link">My Account</span>
  <div class="navbar-dropdown is-boxed">
      @if(!Auth::check())
        <a href="{{ route('login') }}" class="navbar-item">
          Login
        </a>
        <a href="{{ route('register') }}" class="navbar-item">
          Register
        </a>
      @else
        <a href="{{ route(Auth::user()->getRedirectTo()) }}" class="navbar-item">
          Dashboard
          </a>
        <a href="{{ route('users.profile.edit') }}" class="navbar-item">
          Edit Profile
        </a>
        <a href="{{ route('users.profile.view', [ 'username' => Auth::user()->username ]) }}" class="navbar-item">
          View Profile
        </a>
        <a href="{{ route('logout') }}" class="navbar-item">
          Logout
          <i class="sign out"></i>
        </a>
      @endif
  </div>
</div>