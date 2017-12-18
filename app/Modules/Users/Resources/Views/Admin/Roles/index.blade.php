@extends('layouts.admin')

@section('content')
		<div class="pull-left">
			<h1>Roles</h1>
		</div>
		<div class="pull-right">
			<a href="{{ route('admin.roles.add') }}" class="ui right labeled icon button small primary">
		        Add <i class="plus icon"></i>
		    </a>
		</div>

	@if(!$items->count())
		<div class="ui error message">
			<div class="header">Ruh-roh!</div>
	
			<p>No roles found. Create one maybe?</p>
		</div>
	@else

		<table class="ui stackable compact celled table">
			<thead>
				<th>Name</th>
				<th>Level</th>
				<th>Core Role?</th>
				<th>Delete</th>
				<th>Created</th>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr>			
						<td>
							<a href="{{ route('admin.roles.edit', [ 'id' => $item->id ]) }}">
								<strong>{{ $item->name }}</strong>
							</a>
						</td>
						
						<td>
							{{ $item->level }}
						</td>
						<td>
							{{ $item->core_role ? 'Yes' : 'No' }}
						</td>
						<td>
							@if(!$item->core_role)
								<a 
									href="{{ route('admin.roles.delete', [ 'id' => $item->id ]) }}" 
									class="ui right labeled icon button red" 
									onclick="return confirm('Are you sure you want to delete?');"
								>
									Delete 
									<i class="icon trash"></i>
								</a>
							@else
								<span class="ui label">Core Role</span>
							@endif
						</td>
						<td>{{ Core::getAdminDateLong($item->created_at) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

	@endif
@stop
