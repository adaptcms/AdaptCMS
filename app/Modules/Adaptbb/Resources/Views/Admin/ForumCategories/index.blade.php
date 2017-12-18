@extends('layouts.admin')

@section('content')
	<h1>Forum Categories</h1>

	<div class="ui stackable center aligned grid">
      <div class="ui right floated right aligned ten wide column index-nav">
	      <a href="{{ route('plugin.adaptbb.admin.forum_categories.order') }}" class="ui right small green labeled icon button">
                Order Categories
                <i class="refresh icon"></i>
            </a>
            <a href="{{ route('plugin.adaptbb.admin.forum_categories.add') }}" class="ui right small primary labeled icon button">
                Create Category
                <i class="plus icon"></i>
            </a>
      </div>
    </div>

	@if(!$items->count())
		<div class="ui error message">
		    <div class="header">Ruh-roh!</div>
		    <p>No categories found. Create one maybe?</p>
		  </div>
	@else
		<table class="ui stackable compact celled definition table">
			<thead>
				<th></th>
				<th>Name</th>
				<th># of Forums</th>
				<th>Created</th>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr>
						<td class="collapsing">
					        <a href="{{ route('plugin.adaptbb.admin.forum_categories.delete', [ 'id' => $item->id ]) }}" class="ui icon button red" onclick="return confirm('Are you sure you wish to delete?');">
                    <i class="trash icon"></i>
                  </a>
					      </td>
						<td>
							<a href="{{ route('plugin.adaptbb.admin.forum_categories.edit', [ 'id' => $item->id ]) }}">
                <strong>{{ $item->name }}</strong>
              </a>
						</td>
						<td>
							{{ $item->forums->count() }}
						</td>
						<td>{{ Core::getAdminDateLong($item->created_at) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
@stop
