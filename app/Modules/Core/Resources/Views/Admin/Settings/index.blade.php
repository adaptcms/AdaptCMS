@extends('layouts.admin')

    @section('content')
    <div class="settings">
            <h1>Settings</h1>


              <div class="pull-right">
                <a href="{{ route('admin.settings.add') }}" class="ui right right labeled icon button small green">
                    Create New Setting
                    <i class="plus icon"></i>
                </a>
                <a href="{{ route('admin.settings.add_category') }}" class="ui right labeled icon button small primary">
                        Create New Category
                        <i class="plus icon"></i>
                    </a>
        </div>
        <div class="clear"></div>

    @if(empty($items))
                    <div class="ui error message">
                        <div class="header">Ruh-roh!</div>
                        <p>No settings found. Create one maybe?</p>
                      </div>
            @else

    @foreach($items as $category => $settings)
    <h2>{{ $category }}</h2>
                    <table class="ui form stackable compact celled definition table">
                            <thead>
                                    <th></th>
                                    <th>Key</th>
                                    <th>Value</th>
                            </thead>
                            <tbody>
    @foreach($settings as $item)
                                            <tr>
                                                    <td class="collapsing">
                                                    <div class="ui fitted slider checkbox">
                                                      <input type="checkbox" data-id="{{ $item->key }}"> <label></label>
                                                    </div>
                                                  </td>
                                                    <td>
{{ $item->key }}
                                                </td>
                                                <td>
	                                                <div class="field">
                                                <input type="text" name="settings[]" data-key="{{ $item->key }}" value="{{ Settings::get($item->key) }}" class="setting">
	                                                </div>
                                        </tr>
                                @endforeach
                                                            </tbody>
                        <tfoot class="full-width">
                                <tr>
                                  <th></th>
                                  <th colspan="5">
                                    <div class="ui small button" @click.prevent="deleteMany()">
                                      Delete
                                    </div>
                                  </th>
                                </tr>
                          </tfoot>
                </table>
    @endforeach
        <a href="" @click.prevent="saveSettings()" class="ui right labeled icon large button primary">
           Save Changes
           <i class="save icon"></i>
        </a>

        @endif
    </div>
             @stop
