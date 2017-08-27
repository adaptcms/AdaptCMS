@extends('layouts.engine')

@section('content')
	<h1>Users</h1>

<div class="ui stackable center aligned grid">
	<div class="ui four wide column fluid users search">
		<div class="ui icon input">
		<input id="keyword" class="prompt" type="text" placeholder="Search users..."><i class="search icon"></i></div>
		
		<div class="results"></div>
	</div>
	
	<div class="ui right floated right aligned ten wide column index-nav"><span>Show me users by</span>
		
		<div class="ui cms dropdown">
			<div class="text">Role</div>
		    <i class="dropdown icon"></i>
			
			<div class="menu">
				<div class="{{ !Request::get('role_id') ? 'active' : '' }} item"><a href="{{ route('admin.users.index', [ 'role_id' => '', 'status' => Request::get('status', 1) ]) }}">Any</a></div>

			    @foreach($roles as $role)
					<div class="{{ Request::get('role_id') == $role ? 'active' : '' }} item">
						<a href="{{ route('admin.users.index', [ 'role_id' => $role, 'status' => Request::get('status') ]) }}">
							{{ $role }}
						</a>
					</div>
			    @endforeach
			</div>
		</div>
		
		<div class="ui cms dropdown">
			<div class="text">Status</div>
		    <i class="dropdown icon"></i>
			
			<div class="menu">
				<div class="{{ Request::get('status', 1) === '' ? 'active' : '' }} item"><a href="{{ route('admin.users.index', [ 'role_id' => Request::get('role_id'), 'status' => '' ]) }}">Any</a></div>
				
				<div class="{{ Request::get('status', 1) === 1 ? 'active' : '' }} item"><a href="{{ route('admin.users.index', [ 'role_id' => Request::get('role_id'), 'status' => 1 ]) }}">Active</a></div>
				
				<div class="{{ Request::get('status', 1) === '0' ? 'active' : '' }} item"><a href="{{ route('admin.users.index', [ 'role_id' => Request::get('role_id'), 'status' => 0 ]) }}">Pending</a></div>
			</div>
		</div>
			  <a href="{{ route('admin.users.add') }}" class="ui right labeled icon button small primary">
	          Add
	          <i class="plus icon"></i>
	      </a></div>
</div>

	@if(!$items->count())

<div class="ui error message">
	<div class="header">Ruh-roh!</div>
	
	<p>No users found. Create one maybe?</p>
</div>
	@else

<table class="ui stackable compact celled definition table users">
	<thead>
		<th></th>
		
		<th>Username</th>
		
		<th>Name</th>
		
		<th>Roles</th>
		
		<th>Status</th>
		
		<th>Created</th>
	</thead>
	
	<tbody>
		@foreach($items as $item)
		<tr>
			<td class="collapsing">
				<div class="ui fitted slider checkbox">
					<input type="checkbox" data-id="{{ $item->id }}">
					
					<label></label>
				</div>
			</td>
			
			<td><a href="{{ route('admin.users.edit', [ 'id' => $item->id ]) }}"><strong>{{ $item->username }}</strong></a>
				<a href="{{ route('admin.users.login_as', [ 'id' => $item->id ]) }}" class="ui right labeled icon right floated mini green button">
					Login As
					<i class="sign out icon"></i>
				</a></td>
			
			<td>{{ $item->getName() }}</td>
			
			<td>
				<div class="ui labeled button" tabindex="0" data-tooltip="Assigned Roles: {{ implode(', ', $item->roles->pluck('name')->toArray()) }}">
					<div class="ui blue button">
						<i class="group icon"></i> 
						Roles
					</div>
					<a class="ui basic blue left pointing label">
						{{ $item->roles->count() }}
					</a>
				</div>
			</td>
			
			<td>{{ $item->status ? 'Active' : 'Pending' }}</td>
			
			<td>{{ date('F j, Y', strtotime($item->created_at)) }}</td>
		</tr>
		@endforeach
	</tbody>
	
	<tfoot class="full-width">
		<tr>
			<th></th>
			
			<th colspan="5">
				<div class="ui buttons">
					<div class="ui small button" @click.prevent="toggleStatusesMany()">Toggle Status(es)</div>
					
					<div class="ui small button" @click.prevent="deleteMany()">Delete</div>
				</div>
			</th>
		</tr>
		
		<tr>
			<th></th>
			
			<th colspan="5">{{ $items->links() }}</th>
		</tr>
	</tfoot>
</table>
	@endif
@stop
