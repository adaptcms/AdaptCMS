<a href="{{ route('admin.updates.index') }}" class="{{ Route::currentRouteName() == 'admin.updates.index' ? 'active' : '' }} item">
  <span>Updates</span>
  <i class="warning icon"></i>
  @if(Cache::get('cms_updates'))
    <i class="ui circular blue left label">{{ Cache::get('cms_updates') }}</i>
  @endif
</a>
<a class="browse item activate popup">
  <span>Support</span>
  <i class="dropdown icon"></i>
</a>
<div class="ui fluid popup bottom left transition hidden">
  <div class="ui four column relaxed equal height divided grid">
    <div class="column">
      <h4 class="ui header">Fabrics</h4>
      <div class="ui link list">
        <a class="item">Cashmere</a>
        <a class="item">Linen</a>
        <a class="item">Cotton</a>
        <a class="item">Viscose</a>
      </div>
    </div>
    <div class="column">
      <h4 class="ui header">Size</h4>
      <div class="ui link list">
        <a class="item">Small</a>
        <a class="item">Medium</a>
        <a class="item">Large</a>
        <a class="item">Plus Sizes</a>
      </div>
    </div>
    <div class="column">
      <h4 class="ui header">Colored</h4>
      <div class="ui link list">
        <a class="item">Neutrals</a>
        <a class="item">Brights</a>
        <a class="item">Pastels</a>
      </div>
    </div>
    <div class="column">
      <h4 class="ui header">Types</h4>
      <div class="ui link list">
        <a class="item">Knitwear</a>
        <a class="item">Outerwear</a>
        <a class="item">Pants</a>
        <a class="item">Shoes</a>
      </div>
    </div>
  </div>
</div>

<a href="{{ route('admin.updates.browse') }}" class="item">
  <span>Marketplace</span>
  &nbsp;&nbsp;<i class="shopping basket icon"></i>
</a>
  <a href="https://community.adaptcms.com" target="_blank" class="item">
  <span>Community</span>
  &nbsp;&nbsp;<i class="users icon"></i>
</a>
<a href="{{ route('logout') }}" class="item">
  <span>Logout</span>
  &nbsp;&nbsp;<i class="sign out icon"></i>
</a>
<a href="{{ route('home') }}" class="item">
  <span>Home Page</span>
  &nbsp;<i class="home icon"></i>
</a>
<div class="right menu">
  <div class="item">
    <div class="ui transparent icon input">
      <input type="text" placeholder="Search...">
      <i class="inverted search link icon"></i>
    </div>
  </div>
</div>
