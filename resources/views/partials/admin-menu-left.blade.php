<a href="{{ route('admin.dashboard') }}" class="{{ Route::currentRouteName() == 'admin.dashboard' ? 'active teal' : '' }} item">
  Dashboard
</a>
<div class="collapsible item">
  <div class="header">
      Posts
      <i class="caret right icon pull-right"></i>
  </div>
  <div class="menu hidden">
    <a href="{{ route('admin.posts.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.posts.') ? 'active teal' : '' }} item">Posts</a>
    <a href="{{ route('admin.fields.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.fields.') ? 'active teal' : '' }} item">Custom Fields</a>
    <a href="{{ route('admin.tags.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.tags.') ? 'active teal' : '' }} item">Tags</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.categories.') ? 'active teal' : '' }} item">Categories</a>
  </div>
</div>
<a href="{{ route('admin.pages.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.pages.') ? 'active teal' : '' }} item">
  Pages
</a>
<div class="collapsible item">
  <div class="header">
    Media
    <i class="caret right icon pull-right"></i>
  </div>
  <div class="menu hidden">
    <a href="{{ route('admin.albums.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.albums.') ? 'active teal' : '' }} item">
      Albums
    </a>
    <a href="{{ route('admin.files.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.files.') ? 'active teal' : '' }} item">
      Files
    </a>
  </div>
</div>

<div class="collapsible item">
  <div class="header">
    Users
    <i class="caret right icon pull-right"></i>
  </div>
  <div class="menu hidden">
    <a href="{{ route('admin.users.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.users..') ? 'active teal' : '' }} item">
      Users
    </a>
    <a href="{{ route('admin.roles.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.roles.') ? 'active teal' : '' }} item">
      Roles
    </a>
  </div>
</div>

<a href="{{ route('admin.plugins.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.plugins.') ? 'active teal' : '' }} item">
  Plugins
  <div class="ui black label">{{ count(Module::all()) }}</div>
</a>
<a href="{{ route('admin.themes.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.themes.') ? 'active teal' : '' }} item">
  Themes
  <div class="ui black label">{{ \App\Modules\Themes\Models\Theme::getCount() }}</div>
</a>
<a href="{{ route('admin.settings.index') }}" class="{{ strstr(Route::currentRouteName(), 'admin.settings.') ? 'active teal' : '' }} item">
  Settings
</a>

<?php $plugin_links = Core::getAdminPluginLinks(); ?>

@if(!empty($plugin_links))
    @foreach($plugin_links as $module)
        <div class="collapsible item">
          <div class="header">
            {{ $module['name'] }}
            <i class="caret right icon pull-right"></i>
          </div>
          <div class="menu hidden">
            @foreach($module['links'] as $link)
                <a href="{{ route($link['route']) }}" class="{{ Request::url() == route($link['route']) ? 'active' : '' }} item">
                    {{ $link['name'] }}
                </a>
            @endforeach
          </div>
        </div>
    @endforeach
@endif
