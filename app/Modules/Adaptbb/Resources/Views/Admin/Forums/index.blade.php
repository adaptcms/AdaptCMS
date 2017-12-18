@extends('layouts.admin')

@section('content')
	<h1>Forums</h1>

	<div class="ui stackable center aligned grid">
      <div class="ui right floated right aligned ten wide column index-nav">
	      <a href="{{ route('plugin.adaptbb.admin.forums.order') }}" class="ui right small green labeled icon button">
                Order Forums
                <i class="refresh icon"></i>
            </a>
            <a href="{{ route('plugin.adaptbb.admin.forums.add') }}" class="ui right small primary labeled icon button">
                Create Forum
                <i class="plus icon"></i>
            </a>
      </div>
    </div>

	@if(!$items->count())
		<div class="ui error message">
		    <div class="header">Ruh-roh!</div>
		    <p>No forums found. Create one maybe?</p>
		  </div>
	@else
		<table class="ui stackable compact celled definition table">
			<thead>
				<th></th>
				<th>Name</th>
				<th>Category</th>
				<th>Created</th>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr>
						<td class="collapsing">
					        <a href="{{ route('plugin.adaptbb.admin.forums.delete', [ 'id' => $item->id ]) }}" class="ui icon button red" onclick="return confirm('Are you sure you wish to delete?');">
                    <i class="trash icon"></i>
                  </a>
					      </td>
						<td>
							<a href="{{ route('plugin.adaptbb.admin.forums.edit', [ 'id' => $item->id ]) }}">
			                	<strong>{{ $item->name }}</strong>
			              	</a>
						</td>
						<td>
							{{ $categories[$item->category_id] }}
						</td>
						<td>{{ Core::getAdminDateLong($item->created_at) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
@stop
