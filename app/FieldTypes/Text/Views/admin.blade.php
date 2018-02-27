<div class="field">
	{{ Form::label('post_values[' . $field->id . ']', $field->caption) }}
	{{ Form::text('post_values[' . $field->id . ']', $value) }}
</div>
