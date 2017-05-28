<div class="field">
	{{ Form::label('post_values[' . $field->id . ']', $field->caption) }}
	{!! Form::textarea('post_values[' . $field->id . ']', $value, [ 'class' => 'wysiwyg' ]) !!}
</div>
