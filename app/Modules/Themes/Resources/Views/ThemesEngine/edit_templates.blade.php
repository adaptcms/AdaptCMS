@extends('layouts.engine')

@section('content')
	<h1>Edit {{ $theme->name }} Templates</h1>

	<div class="ui right floated right aligned ten wide column index-nav">
		<a href="{{ route('admin.themes.index') }}" class="ui right labeled icon button primary pull-right">
			Back
			<i class="reply icon"></i>
		</a>
	</div>
	<div class="clear"></div>

	@if(empty($files))
		<div class="ui error message">
		    <div class="header">Ruh-roh!</div>
		    <p>No templates found.</p>
		  </div>
	@else
		<table class="ui stackable compact celled definition table">
			<thead>
				<th>Name</th>
				<th class="center-aligned">Edit</th>
			</thead>
			<tbody>
				@foreach($files as $item)
					<tr>
						<td>
							{{ $item }}
						</td>
						<td class="center aligned">
							<a href="{{ route('admin.themes.edit_template', [ 'id' => $theme->id, 'path' => base64_encode($item) ]) }}" class="ui right icon button"><i class="pencil icon"></i></a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
@stop
