<a href="{{ route('admin.updates.index') }}" class="{{ Route::currentRouteName() == 'admin.updates.index' ? 'active' : '' }} item">
  <span>Updates <i class="warning icon"></i></span>
  @if(Core::getUpdatesCount())
    <i class="ui circular blue left label">{{ Core::getUpdatesCount() }}</i>
  @endif
</a>
<a href="{{ route('admin.updates.browse') }}" class="item">
  <span>Marketplace</span>
  &nbsp;&nbsp;<i class="shopping basket icon"></i>
</a>
  <a href="https://community.adaptcms.com" target="_blank" class="item">
  <span>Community</span>
  &nbsp;&nbsp;<i class="users icon"></i>
</a>
<a href="https://learn.adaptcms.com" target="_blank" class="item">
<span>Learn</span>
&nbsp;&nbsp;<i class="book icon"></i>
</a>
<a href="{{ route('logout') }}" class="item">
  <span>Logout</span>
  &nbsp;&nbsp;<i class="sign out icon"></i>
</a>
<a href="{{ route('home') }}" class="item">
  <span>Public Site</span>
  &nbsp;<i class="home icon"></i>
</a>
<div class="right menu">
  <div class="item">
    <div class="ui fluid posts search">
        <div class="ui transparent icon input">
          <input type="text" placeholder="Search posts..." class="prompt font-color-white">
          <i class="inverted search link icon"></i>
        </div>
        <div class="results"></div>
    </div>
  </div>
</div>
