@foreach(Core::getData('pages', 'all', [], [], 3) as $page)
    @if($page->slug == 'home')
        <a href="{{ route('home') }}" class="{{ Request::url() == route('home') ? 'active' : '' }} item">
        {{ $page->name }}
    </a>
    @else
        <a href="{{ route('pages.view', [ 'slug' => $page->slug ]) }}" class="{{ Request::url() == route('pages.view', [ 'slug' => $page->slug ]) ? 'active' : '' }} item">
            {{ $page->name }}
        </a>
    @endif
@endforeach

@foreach(Core::getData('categories', 'all', [], [ 'ord', 'asc' ], 3) as $category)
    <a href="{{ route('categories.view', [ 'slug' => $category->slug ]) }}" class="{{ Request::url() == route('categories.view', [ 'slug' => $category->slug ]) ? 'active' : '' }} item">
        {{ $category->name }}
    </a>
@endforeach

<a href="{{ route('albums.index') }}" class="{{ Request::url() == route('albums.index') ? 'active' : '' }} item">
Albums
</a>

<div class="item right menu">
  @if(!Auth::check())
    <div class="ui buttons tiny">
        <a href="{{ route('login') }}" class="ui basic inverted button mobile only">
          Login
        </a>
        <a href="{{ route('register') }}" class="ui basic inverted button mobile only">
          Register
        </a>

        <a href="{{ route('login') }}" class="ui basic button primary computer only tablet only">
          Login
        </a>
        <a href="{{ route('register') }}" class="ui basic button green computer only tablet only">
          Register
        </a>
    </div>
  @else
      <div class="ui simple dropdown item">
      My Account
      <i class="dropdown icon"></i>
      <div class="menu">
          <a href="{{ route(Auth::user()->getRedirectTo()) }}" class="item">
      Dashboard
      </a>
          <a href="{{ route('users.profile.edit') }}" class="item">
      Edit Profile
    </a>
    <a href="{{ route('users.profile.view', [ 'username' => Auth::user()->username ]) }}" class="item">
      View Profile
    </a>
        <a href="{{ route('logout') }}" class="item">
          Logout
          <i class="sign out"></i>
        </a>
      </div>
    </div>
  @endif
</div>
