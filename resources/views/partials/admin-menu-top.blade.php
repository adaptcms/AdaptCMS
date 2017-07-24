<a href="{{ route('admin.updates.index') }}" class="{{ Route::currentRouteName() == 'admin.updates.index' ? 'active' : '' }} item" data-tooltip="Updates" data-position="bottom center" data-inverted>
  <span>Updates <i class="warning icon"></i></span>
  @if(Core::getUpdatesCount())
    <i class="ui circular blue left label">{{ Core::getUpdatesCount() }}</i>
  @endif
</a>
<a href="{{ route('admin.updates.browse') }}" class="{{ Route::currentRouteName() == 'admin.updates.browse' ? 'active' : '' }} item" data-tooltip="Marketplace" data-position="bottom center" data-inverted>
  <span>Marketplace</span>
  &nbsp;&nbsp;<i class="shopping basket icon"></i>
</a>
  <a href="https://community.adaptcms.com" target="_blank" class="item" data-tooltip="Community" data-position="bottom center" data-inverted>
  <span>Community</span>
  &nbsp;&nbsp;<i class="users icon"></i>
</a>
<a href="https://learn.adaptcms.com" target="_blank" class="item" data-tooltip="Learn" data-position="bottom center" data-inverted>
<span>Learn</span>
&nbsp;&nbsp;<i class="book icon"></i>
</a>
<a href="{{ route('logout') }}" class="item" data-tooltip="Logout" data-position="bottom center" data-inverted>
  <span>Logout</span>
  &nbsp;&nbsp;<i class="sign out icon"></i>
</a>
<a href="{{ route('home') }}" class="item" data-tooltip="Public Site" data-position="bottom center" data-inverted>
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
