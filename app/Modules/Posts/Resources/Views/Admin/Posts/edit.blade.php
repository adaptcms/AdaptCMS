@extends('layouts.engine')

@section('content')
	<h1>Edit Post</h1>

      <div class="ui top attached tabular tabs menu">
	  <a class="item active" data-tab="post">Post</a>
	  <a class="item" data-tab="revisions">Revisions</a>
	</div>

    <div class="ui bottom attached tab segment active" data-tab="post">
		{{ Form::model($model, [ 'class' => 'ui form posts' ]) }}

			<div class="alert alert-danger">
			        <ul>
			            @foreach ($errors as $fields)
			            	@foreach($fields as $error)
			                	<li>{{ $error }}</li>
			                @endforeach
			            @endforeach
			        </ul>
			    </div>

			<div class="required field">
				{{ Form::label('name') }}
				{{ Form::text('name', $model->name, [ 'v-model' => 'name' ]) }}
			</div>

			<div class="required field">
				{{ Form::label('slug', 'URL Slug') }}
				{{ Form::text('slug', $model->slug, [ 'v-model' => 'slug' ]) }}
			</div>

			@foreach($fields as $field)
				@include('partials/field_types/' . $field->field_type, [
						'value' => $post_data['post_data'][$field->slug],
						'images' => $images,
						'files' => $files
				])
			@endforeach

			<div class="field">
				{{ Form::label('related_posts[]', 'Related Posts') }}
				{{ Form::select('related_posts[]', $posts, $model->getRelatedVal(), [ 'class' => 'ui dropdown', 'multiple' ]) }}
			</div>

			<div class="field">
				{{ Form::label('tags', 'Tags') }}
				{{ Form::text('tags', $model->getStringTags(), [ 'class' => 'tagsInput' ]) }}
			</div>

			<div class="field">
                {{ Form::label('meta_keywords') }}

                <div class="ui fluid multiple search selection dropdown allowAdditions">
                    {{ Form::hidden('meta_keywords') }}
                    <i class="dropdown icon"></i>
                    <div class="default text">Keywords</div>
                    <div class="menu"></div>
                </div>
            </div>

            <div class="field">
                {{ Form::label('meta_description') }}
                {{ Form::textarea('meta_description') }}
            </div>

			<div class="inline field">
			    <div class="ui toggle checkbox">
			      {{ Form::checkbox('status', true, true, [ 'class' => 'hidden' ]) }}
			      {{ Form::label('status') }}
			    </div>
			  </div>

			{{ Form::submit('Save', [ 'class' => 'ui button primary' ]) }}

		{{ Form::close() }}
    </div>

	<div class="ui bottom attached tab segment" data-tab="revisions">
		<table class="ui compact celled definition table">
			<thead>
				<th>Updated</th>
				<th>Action</th>
			</thead>
			<tbody>
				@foreach($model->postRevisions as $revision)
					<tr>
						<td><?php echo date('F j, Y g:i A', strtotime($revision->created_at)) ?></td>
						<td>
							<a href="{{ route('admin.posts.restore', [ 'id' => $revision->id ]) }}" class="ui right primary labeled icon button">Restore <i class="refresh right icon"></i></a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@stop
