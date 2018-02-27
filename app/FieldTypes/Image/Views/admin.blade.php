<div class="field">
	{{ Form::label('post_values[' . $field->id . ']', $field->caption) }}
	{{ Form::select('post_values[' . $field->id . ']', $images, $value, [ 'class' => 'ui dropdown', 'placeholder' => 'Pick...' ]) }}
</div>

