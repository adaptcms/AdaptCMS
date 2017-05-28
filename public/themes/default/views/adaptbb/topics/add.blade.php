<div class="ui grid">
	<div class="twelve wide column centered">
		<h1>Community Forums</h1>
		<h2>Create Topic in {{ $forum->name }}</h2>

		<div class="ui segment right aligned">
			<a href="{{ route('plugin.adaptbb.forums.view', [ 'slug' => $forum->slug ]) }}" class="ui right labeled icon button primary">
				Go Back
				<i class="reply icon"></i>
			</a>
		</div>

		<div class="ui segment">
			{!! Theme::partial('adaptbb.form_errors') !!}

			{{ Form::model($model, [ 'class' => 'ui form very padded' ]) }}
				<div class="required field">
					{{ Form::label('name') }}
					{{ Form::text('name') }}
				</div>
				<div class="required field">
					{{ Form::label('message') }}
					{{ Form::textarea('message') }}
				</div>

				<button type="submit" class="ui right labeled icon button green">
					Post It
					<i class="save icon"></i>
				</button>

			{{ Form::close() }}
		</div>
	</div>
</div>
