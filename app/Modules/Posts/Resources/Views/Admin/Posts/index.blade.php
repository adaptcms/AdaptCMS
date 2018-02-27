@extends('layouts.admin')

@section('content')
    <h1>Posts</h1>

    <div class="ui stackable center aligned grid">
        <div class="ui four wide column fluid posts search">
            <div class="ui icon input">
                <input id="keyword" class="prompt" type="text" placeholder="Search posts...">
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>

        <div class="ui right floated right aligned float right ten wide column index-nav">
            <span>Show me posts by</span>
            <div class="ui cms dropdown">
                <div class="text">
                  Category
                </div>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <div class="{{ !Request::get('category_id') ? 'active' : '' }} item">
                        <a href="{{ route('admin.posts.index', [ 'category_id' => '', 'status' => Request::get('status') ]) }}">Any</a>
                    </div>

                    @foreach($categories as $category)
                        <div class="{{ Request::get('category_id') == $category->id ? 'active' : '' }} item">
                            <a href="{{ route('admin.posts.index', [ 'category_id' => $category->id, 'status' => Request::get('status') ]) }}">{{ $category->name }}</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="ui cms dropdown">
                <div class="text">
                  Status
                </div>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <div class="{{ Request::get('status', 1) === '' ? 'active' : '' }} item">
                        <a href="{{ route('admin.posts.index', [ 'category_id' => Request::get('category_id'), 'status' => '' ]) }}">Any</a>
                    </div>

                    <div class="{{ Request::get('status', 1) === 1 ? 'active' : '' }} item">
                        <a href="{{ route('admin.posts.index', [ 'category_id' => Request::get('category_id'), 'status' => 1 ]) }}">Active</a>
                    </div>
                    <div class="{{ Request::get('status', 1) === '0' ? 'active' : '' }} item">
                        <a href="{{ route('admin.posts.index', [ 'category_id' => Request::get('category_id'), 'status' => 0 ]) }}">Pending</a>
                    </div>
                </div>
            </div>

            <div class="ui cms dropdown">
                <button class="ui right labeled icon small primary button">
                    Add
                    <i class="plus icon"></i>
                </button>
                <div class="menu">
                    @foreach($categories as $category)
                        <div class="item">
                            <a href="{{ route('admin.posts.add', [ 'category_id' => $category->id ]) }}">{{ $category->name }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if(!$items->count())
        <div class="ui error message">
            <div class="header">Ruh-roh!</div>
            <p>No posts found. Create one maybe?</p>
        </div>
    @else
        <table class="ui compact responsive celled definition stackable table posts">
            <thead>
                <th></th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
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
                            <a href="{{ route('admin.posts.edit', [ 'id' => $item->id ]) }}">
                                <strong>{{ $item->name }}</strong>
                            </a>
                        </td>
                        <td>{{ $item->category->name }}</td>
                        <td>{{ $item->status ? 'Active' : 'Pending' }}</td>
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
                        <div class="ui buttons">
                            <div class="ui small button" @click.prevent="toggleStatusesMany()">
                                Flip Status
                            </div>
                            <div class="ui small button" @click.prevent="deleteMany()">
                                Delete
                            </div>
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
    @endif
@stop