@extends('layouts.admin')

@section('content')
    <h1>AdaptCMS Updates</h1>

    <div class="ui red padded segment">
        <h2>CMS Core</h2>

        <div>
            <a
              href="{{ route('admin.updates.upgrade', [ 'type' => 'bleeding_edge' ]) }}"
              class="ui right labeled icon primary big button {{ Cache::has('bleedinge_edge_update') }}"
              onclick="return confirm('Are you sure? No turning back. Bugs may be aplenty...');"
            >
                Bleeding Edge <i class="fire icon"></i>
            </a>
            <br>

            @if(Cache::get('bleedinge_edge_update'))
                <?php $bleedinge_edge = json_decode(Cache::get('cms_current_version'), true); ?>

                <small>Upgrade Available <i class="upload icon"></i></small><br />
                <small>
                    Last Updated:
                    {{ Core::getAdminDateLong($bleedinge_edge['updated_at']) }}
                </small>
            @else
                <small>Up To Date <i class="checkmark icon"></i></small>
            @endif
        </div>
        <div>
            <a href="{{ route('admin.updates.upgrade', [ 'type' => 'normal' ]) }}" class="ui right labeled icon green big button {{ !Cache::has('cms_latest_version') ? 'disabled' : '' }}">
                {{ Cache::has('cms_latest_version_name') ? Cache::get('cms_latest_version_name') : Core::getVersion() }} Release
                <i class="home icon"></i>
            </a>
            <br>

            @if(Cache::has('cms_latest_version_name'))
                <small>Upgrade Available <i class="upload icon"></i></small>
            @else
                <small>Up To Date <i class="checkmark icon"></i></small>
            @endif
        </div>
    </div>

    <div class="ui text container">
        <h2>Plugins</h2>

        @if(!Core::getAddonUpdates('plugins'))
            <p>
              No plugin updates
            </p>

            <a href="{{ route('admin.updates.browse', [ 'module_type' => 'plugins']) }}" class="ui right labeled icon floated primary button pull-right">
              <i class="shopping basket icon"></i> Find More Plugins
            </a>
        @else
            <table class="ui form compact celled definition table plugins update">
              <thead>
                <th>Update</th>
                <th>Name</th>
                <th>Current Version</th>
                <th>Latest Version</th>
                <th>Description</th>
              </thead>
              <tbody>
                @foreach(Core::getAddonUpdates('plugins') as $module)
                    <tr>
                      <td class="collapsing">
                        <div class="ui fitted slider checkbox">
                          <input type="checkbox" class="plugin-id" v-model="module_ids[{{ $module['id'] }}]" checked> <label></label>
                        </div>
                      </td>
                      <td>{{ $module['name'] }}</td>
                      <td>{{ Module::get($module['slug'] . '::version') }}</td>
                      <td>{{ $module['latest_version']['version'] }}</td>
                      <td>{{ $module['latest_version']['notes'] }}</td>
                    </tr>
                @endforeach
              </tbody>
              <tfoot class="full-width">
                <tr>
                  <th></th>
                  <th colspan="4">
                    <a href="{{ route('admin.updates.browse', [ 'module_type' => 'plugin']) }}" class="ui right labeled icon floated small primary button pull-right">
                      <i class="shopping basket icon"></i> Find More Plugins
                    </a>
                    <a href="" @click.prevent="update()" class="ui small button">
                      Update
                    </a>
                    <a href="" @click.prevent="updateAll()" class="ui small button">
                      Update All
                    </a>
                  </th>
                </tr>
              </tfoot>
            </table>
        @endif
    </div>

    <div class="ui text container">
        <h2>Themes</h2>

        @if(!Core::getAddonUpdates('themes'))
            <p>
              No theme updates
            </p>

            <a href="{{ route('admin.updates.browse', [ 'module_type' => 'themes']) }}" class="ui right labeled icon floated primary button pull-right">
              <i class="shopping basket icon"></i> Find More Themes
            </a>
        @else
            <table class="ui form compact celled definition table themes update">
              <thead>
                <th>Update</th>
                <th>Name</th>
                <th>Current Version</th>
                <th>Latest Version</th>
                <th>Description</th>
              </thead>
              <tbody>
                @foreach(Core::getAddonUpdates('themes') as $module)
                    <tr>
                      <td class="collapsing">
                          <div class="ui fitted slider checkbox">
                            <input type="checkbox" class="theme-id" v-model="module_ids[{{ $module['id'] }}]" checked> <label></label>
                          </div>
                      </td>
                      <td>{{ $module['name'] }}</td>
                      <td>{{ $module['theme']->getConfig('version') }}</td>
                      <td>{{ $module['latest_version']['version'] }}</td>
                      <td>{{ $module['latest_version']['notes'] }}</td>
                    </tr>
                @endforeach
              </tbody>
              <tfoot class="full-width">
                <tr>
                  <th></th>
                  <th colspan="4">
                    <a href="{{ route('admin.updates.browse', [ 'module_type' => 'theme']) }}" class="ui right labeled icon floated small green button pull-right">
                      <i class="shopping basket icon"></i> Find More Themes
                    </a>
                    <a href="" @click.prevent="update()" class="ui small button">
                      Update
                    </a>
                    <a href="" @click.prevent="updateAll()" class="ui small button">
                      Update All
                    </a>
                  </th>
                </tr>
              </tfoot>
            </table>
        @endif
    </div>
@stop
