<div class="required field">
	{{ Form::label('post_values[' . $field->id . ']', $field->caption) }}
	{!! Form::textarea('post_values[' . $field->id . ']', '', [ 'class' => 'wysiwyg code-view' ]) !!}
	
	@if(!empty($model_id))
		<div class="api-call" data-url="/admin/api/post_data?post_id={{ $model->id }}&field_id={{ $field->id }}"></div>
	@endif
</div>

