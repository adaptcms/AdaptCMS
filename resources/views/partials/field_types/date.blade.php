<div class="field">
	{{ Form::label('post_values[' . $field->id . ']', $field->caption) }}

	<div class="ui calendar">
	  <div class="ui input left icon">
	    <i class="calendar icon"></i>
	    {{ Form::text('post_values[' . $field->id . ']', $value) }}
	  </div>
	</div>
</div>
