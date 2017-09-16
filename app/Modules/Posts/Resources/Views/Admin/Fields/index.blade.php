@extends('layouts.engine')

@section('content')
	<h1>Fields</h1>

	<div class="ui stackable center aligned grid">
		<div class="ui four wide column fluid fields search">
		  <div class="ui icon input">
		    <input id="keyword" class="prompt" type="text" placeholder="Search fields...">
		    <i class="search icon"></i>
		  </div>
		  <div class="results"></div>
		</div>

	  <div class="ui right floated right aligned ten wide column index-nav">
		  <span>Show me fields by</span>
	  <div class="ui cms dropdown">
	    <div class="text">
	      Category
	    </div>
	    <i class="dropdown icon"></i>
	    <div class="menu">
		    <div class="{{ !Request::get('category_id') ? 'active' : '' }} item">
			    <a href="{{ route('admin.fields.index', [ 'category_id' => '', 'field_type' => Request::get('field_type') ]) }}">Any</a>
		    </div>

		    @foreach($categories as $category)
		    	<div class="{{ Request::get('category_id') == $category->id ? 'active' : '' }} item">
			    	<a href="{{ route('admin.fields.index', [ 'category_id' => $category->id, 'field_type' => Request::get('field_type') ]) }}">{{ $category->name }}</a>
		    	</div>
		    @endforeach
	    </div>
	  </div>

		  <div class="ui cms dropdown">
		    <div class="text">
		      Field Type
		    </div>
		    <i class="dropdown icon"></i>
		    <div class="menu">
			    <div class="{{ !Request::get('field_type') ? 'active' : '' }} item">
				    <a href="{{ route('admin.fields.index', [ 'category_id' => Request::get('category_id'), 'field_type' => '' ]) }}">Any</a>
			    </div>
			    @foreach($field_types as $key => $type)
			    	<div class="{{ Request::get('field_type') == $key ? 'active' : '' }} item">
				    	<a href="{{ route('admin.fields.index', [ 'category_id' => Request::get('category_id'), 'field_type' => $key ]) }}">{{ $type }}</a>
			    	</div>
				@endforeach
		    </div>
		  </div>
		  <a href="{{ route('admin.fields.order') }}" class="ui right labeled icon tiny green button">
          Order Fields
          <i class="refresh icon"></i>
      </a>
        <a href="{{ route('admin.fields.add') }}" class="ui right labeled icon tiny primary button">
            Create Field
            <i class="plus icon"></i>
        </a>
	  </div>
	</div>

	@if(!$items->count())
		<div class="ui error message">
            <div class="header">Ruh-roh!</div>
            <p>No fields found. Create one maybe?</p>
          </div>
	@else
		<table class="ui stackable tablet stackable compact celled definition table fields">
			<thead>
				<th></th>
				<th>Name</th>
				<th>Caption</th>
				<th>Field Type</th>
				<th>Category</th>
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
							<a href="{{ route('admin.fields.edit', [ 'id' => $item->id ]) }}"><strong>{{ $item->name }}</strong></a>
						</td>
						<td>{{ $item->caption }}</td>
						<td>{{ $field_types[$item->field_type] }}</td>
						<td>{{ $item->category->name }}</td>
						<td>{{ date('F j, Y', strtotime($item->created_at)) }}</td>
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
	@endif
@stop
