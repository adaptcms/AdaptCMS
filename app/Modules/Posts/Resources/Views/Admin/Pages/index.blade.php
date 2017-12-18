@extends('layouts.admin')

@section('content')
	<h1>Pages</h1>

    <div class="ui stackable center aligned grid">
	    <div class="ui four wide column fluid pages search">
	      <div class="ui icon input">
	        <input id="keyword" class="prompt" type="text" placeholder="Search pages...">
	        <i class="search icon"></i>
	      </div>
	      <div class="results"></div>
	    </div>

      <div class="ui right floated right aligned ten wide column index-nav">
              <span>Show me pages by</span>
          <div class="ui cms dropdown">
            <div class="text">
              Status
            </div>
            <i class="dropdown icon"></i>
            <div class="menu">
	            <div class="{{ Request::get('status', 1) === '' ? 'active' : '' }} item">
				    <a href="{{ route('admin.pages.index', [ 'status' => '' ]) }}">Any</a>
			    </div>
                <div class="{{ Request::get('status', 1) === 1 ? 'active' : '' }} item">
                    <a href="{{ route('admin.pages.index', [ 'status' => 1 ]) }}">Active</a>
                </div>
                <div class="{{ Request::get('status', 1) === '0' ? 'active' : '' }} item">
                    <a href="{{ route('admin.pages.index', [ 'status' => 0 ]) }}">Pending</a>
                </div>
              </div>
              </div>
              <a href="{{ route('admin.pages.order') }}" class="ui small button green labeled icon button">
                  Order Pages
                  <i class="refresh icon"></i>
              </a>
                <a href="{{ route('admin.pages.add') }}" class="ui right small primary labeled icon button">
                    Create Page
                    <i class="plus icon"></i>
                </a>
          </div>
        </div>

	@if(!$items->count())
        <div class="ui error message">
            <div class="header">Ruh-roh!</div>
            <p>No pages found. Create one maybe?</p>
          </div>
	@else
        <table class="ui compact celled definition table pages">
			<thead>
                <th></th>
				<th>Name</th>
				<th>Status</th>
                <th>Author</th>
                <th>Created</th>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr>
						<td class="collapsing">
							@if($item->slug != 'home')
								<div class="ui fitted slider checkbox">
									<input type="checkbox" data-id="{{ $item->id }}"> <label></label>
								</div>
							@endif
						</td>
						<td>
							<a href="{{ route('admin.pages.edit', [ 'id' => $item->id ]) }}">
								<strong>{{ $item->name }}</strong>
							</a>
						</td>
                        <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
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
