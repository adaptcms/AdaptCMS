<div class="ui card">
  <div class="content">
      <div class="right floated meta">{{ Core::getAdminDateLong($module->updated_at) }}</div>

        @if(!empty($module->user->logo))
            <img class="ui avatar image" src="{{ $module->user->logo }}">
        @endif
        @if(!empty($module->user->company))
            <span>
                {{ $module->user->company }}
            </span>
        @endif
  </div>
  <div class="image">
    @if(!empty($module->image)))
        <img src="{{ $module->image }}">
    @else
        <img src="https://placehold.it/350x350?text=Plugin">
    @endif
  </div>
  <div class="content">
      <h4 class="text-align-center">{{ $module->name }}</h4>

      <p>{{ str_limit($module->description, 100) }}</p>

    <span class="right floated">
        <div
          class="ui star rating disabled"
          data-rating="{{ $module->avg_rating }}"
          data-max-rating="5"
        ></div>
      <small class="numbers">({{ number_format($module->total_ratings) }})</small>
    </span>
    <i class="bar chart icon"></i>
    {{ $module->views }} view(s)
  </div>
  <div class="extra content">
    <div class="ui center aligned">
      <div class="ui buttons mobile only tablet only">
          <a href="{{ route(($module->module_type == 'plugin' ? 'admin.updates.install_plugin' : 'admin.updates.install_theme'), [ 'id' => $module->id ]) }}" class="small ui button primary">
                Install
          </a>
          <a href="https://www.adaptcms.com/community/forum/{{ $module->slug }}" class="small ui button green">
                Community
          </a>
      </div>
      <div class="ui buttons computer only">
          <a href="{{ route(($module->module_type == 'plugin' ? 'admin.updates.install_plugin' : 'admin.updates.install_theme'), [ 'id' => $module->id ]) }}" class="ui right labeled icon button primary">
                install
                <i class="plus icon"></i>
          </a>
          <a href="https://www.adaptcms.com/community/forum/{{ $module->slug }}" class="ui right labeled icon button green">
                Community
                <i class="users icon"></i>
          </a>
      </div>
    </div>
      <div class="ui labels text-align-center margin-top-10">
            @foreach($module->tags as $tag)
                <span class="ui label">
                    {{ $tag->name }}
                </span>
            @endforeach
      </div>
  </div>
</div>
