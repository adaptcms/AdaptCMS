@extends('layouts.admin')

@section('content')
    <h1>Tags</h1>

    <div class="ui stackable center aligned grid">
        <div class="ui four wide column fluid tags search">
            <div class="ui icon input">
                <input id="keyword" class="prompt" type="text" placeholder="Search tags...">
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>

        <div class="ui right floated right aligned ten wide column index-nav">
            <a href="{{ route('admin.tags.add') }}" class="ui right small primary labeled icon button">
                Create Tag
                <i class="plus icon"></i>
            </a>
        </div>
    </div>

    @if(!$items->count())
        <div class="ui error message">
            <div class="header">Ruh-roh!</div>
            <p>No tags found. Create one maybe?</p>
        </div>
    @else
        <table class="ui stackable compact celled definition table tags">
            <thead>
                <th></th>
                <th>Name</th>
                <th>Author</th>
                <th>Created</th>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="collapsing">
                            <div class="ui fitted slider checkbox">
                              <input type="checkbox" data-id="{{ $item->id }}"> <label></label>
                            </div>
                          </td>
                        <td>
                            <a href="{{ route('admin.tags.edit', [ 'id' => $item->id ]) }}">
                                <strong>{{ $item->name }}</strong>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', [ 'id' => $item->user_id ]) }}">
                                {{ $item->user->getName() }}
                            </a>
                        </td>
                        <td>{{ Core::getAdminDateLong($item->created_at) }}</td>
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
                <tr>
                    <th></th>
                    <th colspan="5">
                        {{ $items->links() }}
                    </th>
               </tr>
            </tfoot>
        </table>

        {{ $items->links() }}
    @endif
@stop