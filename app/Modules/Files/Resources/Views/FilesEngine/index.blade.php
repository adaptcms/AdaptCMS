@extends('layouts.engine')

@section('content')
	<h1>Files</h1>

	<div class="ui stackable center aligned grid">
		<div class="ui four wide column fluid files search">
		  <div class="ui icon input">
		    <input id="keyword" class="prompt" type="text" placeholder="Search files...">
		    <i class="search icon"></i>
		  </div>
		  <div class="results"></div>
		</div>

	  <div class="ui right floated right aligned ten wide column index-nav">
		  <span>Show me files by</span>
		  <div class="ui inline dropdown">
		    <div class="text">
		      File Type
		    </div>
		    <i class="dropdown icon"></i>
		    <div class="menu">
			    <div class="{{ !Request::get('file_type') ? 'active' : '' }} item">
				    <a href="{{ route('admin.files.index', [ 'file_type' => '' ]) }}">Any</a>
			    </div>
			    @foreach($file_types as $key => $type)
			    	<div class="{{ Request::get('file_type') == $key ? 'active' : '' }} item">
				    	<a href="{{ route('admin.files.index', [ 'file_type' => $key ]) }}">{{ $type }}</a>
			    	</div>
				@endforeach
		    </div>
		  </div>
      <a href="{{ route('admin.files.add') }}" class="ui right labeled icon button small primary">
          Upload
          <i class="upload icon"></i>
      </a>
	  </div>
	</div>

	@if(!$items->count())
		<div class="ui error message">
		    <div class="header">Ruh-roh!</div>
		    <p>No files found. Create one maybe?</p>
		  </div>
	@else
		<table class="ui stackable compact celled definition table files">
			<thead>
				<th></th>
				<th>File Name</th>
				<th>Type</th>
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
							<a href="{{ route('admin.files.edit', [ 'id' => $item->id ]) }}"><strong>{{ $item->filename }}</strong></a>
						</td>
						<td>{{ $item->file_type }}</td>
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
