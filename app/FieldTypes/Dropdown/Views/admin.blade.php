<div class="field">
	{{ Form::label('post_values[' . $field->id . ']', $field->caption) }}
	{{ Form::select('post_values[' . $field->id . ']', $field->getSetting('options'), $value, [ 'class' => 'ui dropdown '. ($field->getSetting('multi') ? 'multiple' : ''), 'placeholder' => 'Pick...' ]) }}
</div>

