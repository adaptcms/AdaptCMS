@extends('layouts.admin')

@section('content')
	<h1>Themes</h1>

	<div class="ui stackable center aligned grid">
		<div class="ui left floated left aligned eight wide column index-nav">
			<a href="{{ route('admin.updates.browse', [ 'module_type' => 'themes' ]) }}" class="ui right labeled icon button primary">
				Marketplace
				<i class="shopping basket icon"></i>
			</a>
			<a href="{{ route('admin.updates.index') }}" class="ui right labeled icon button green">
				Updates
				<i class="warning icon"></i>
			</a>
		</div>
	    <div class="ui right floated right aligned eight wide column index-nav">
			<a class="ui button primary right" href="{{ route('admin.themes.add') }}">
				Add Theme <i class="plus icon"></i>
			</a>
	    </div>
	</div>

	@if(!$items->count())
        <div class="ui error message">
			<div class="header">Ruh-roh!</div>
			<p>No themes found. Create one maybe?</p>
		</div>
	@else
		<table class="ui stackable compact celled definition table themes">
			<thead>
				<th></th>
				<th>Name</th>
				<th>Status</th>
				<th>Created</th>
				<th></th>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr>
						<td class="collapsing">
							@if(!empty($item->id) && $item->id > 1)
					        <div class="ui fitted slider checkbox">
					          <input type="checkbox" data-id="{{ $item->id }}"> <label></label>
					        </div>
					      </td>
							@endif
						<td>
							@if(!empty($item->id) && $item->id > 1)
								<a href="{{ route('admin.themes.edit', [ 'id' => $item->id ]) }}">
									<strong>{{ $item->name }}</strong>
								</a>
							@else
								<strong>{{ $item->name }}</strong>
							@endif
						</td>
						<td>{{ $item->status ? 'Active' : 'Pending' }}</td>
						<td>{{ !empty($item->created_at) ? Core::getAdminDateLong($item->created_at) : '' }}</td>
						<td>
							@if(empty($item->id))
								<a href="{{ route('admin.themes.activate', [ 'slug' => $item->slug ]) }}" class="ui right labeled icon button green">
									Activate <i class="warning icon"></i>
								</a>
							@else
								@if(!$item->status)
									<a href="{{ route('admin.themes.status', [ 'id' => $item->id ]) }}" class="ui right labeled icon button green">
										Make Active <i class="check icon"></i>
									</a>
								@endif
								
								<a href="{{ route('admin.themes.edit_templates', [ 'id' => $item->id ]) }}" class="ui right labeled icon button primary">
									Files <i class="pencil icon"></i>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>

			<tfoot class="full-width">
			    <tr>
			    	<th></th>
			    	<th colspan="5">
						<div class="ui buttons">
					        <div class="ui small button" @click.prevent="deleteMany()">
					          Delete
					        </div>
						</div>
			    	</th>
			    </tr>
			  </tfoot>
		</table>
		
	@endif
@stop
