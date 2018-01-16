@extends('layouts.admin')

@section('content')

    <h1>Plugins</h1>

    <div class="ui eight wide column pull-right index-nav">
        <a href="{{ route('admin.updates.browse', [ 'module_type' => 'plugins' ]) }}" class="ui right labeled icon button primary">
            Marketplace
            <i class="shopping basket icon"></i>
        </a>
        <a href="{{ route('admin.updates.index') }}" class="ui right labeled icon button green">
            Updates
            <i class="warning icon"></i>
        </a>
    </div>
    <div class="clear"></div>

    <table class="ui stackable compact celled table plugins">
        <thead>
            <th>Name</th>
            <th>Version</th>
            <th>Description</th>
            <th></th>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['version'] }}</td>
                    <td>{!! $item['description'] !!}</td>
                    <td>
                        @if(!$item['enabled'])
                            <a href="{{ route('admin.plugins.install', [ 'slug' => ucfirst($item['slug']) ]) }}" class="ui right labeled icon button primary">
                                Install <i class="plus icon"></i>
                            </a>
                        @elseif(!in_array($item['name'], $core_modules))
                            <a href="{{ route('admin.plugins.uninstall', [ 'slug' => ucfirst($item['slug']) ]) }}" class="ui right labeled icon button red">
                                Uninstall <i class="trash icon"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
