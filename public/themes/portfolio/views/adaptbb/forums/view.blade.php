<h1>Community Forums</h1>
<h2>{{ $forum->name }}</h2>

<div class="ui segment right aligned">
	<a href="{{ route('plugin.adaptbb.topics.add', [ 'forum_slug' => $forum->slug ]) }}" class="ui right labeled icon button primary large">
		Create Topic
		<i class="plus icon"></i>
	</a>
</div>

@if(!$topics->count())
	<div class="ui text container center aligned very padded">
		<p>No topics made. <a href="{{ route('plugin.adaptbb.topics.add', [ 'forum_slug' => $forum->slug ]) }}">Get the party started!</a>
	</div>
@else
	<!-- TABLET AND DESKTOP -->
	<table class="ui red stackable very padded large topics table tablet only computer only">
		<thead>
			<th>Topic</th>
			<th>Replies</th>
			<th class="right aligned">Views</th>
			<th class="right aligned">Last Reply</th>
		</thead>
		<tbody>
			@foreach($topics as $topic)
				<tr>
					<td>
						<a href="{{ route('plugin.adaptbb.topics.view', [ 'forum_slug' => $forum->slug, 'topic_slug' => $topic->slug ]) }}">
							<strong>{{ $topic->name }}</strong>
						</a><br />

						<small>By: <a href="{{ route('users.profile.view', [ 'username' => $topic->user->username ]) }}">{{ $topic->user->username }}</a> @ {{ Core::getDateLong($topic->created_at) }}</small>
					</td>
					<td>
						{{ number_format($topic->replies_count) }}
					</td>
					<td class="right aligned">
						{{ number_format($topic->views) }}
					</td>
					<td class="right aligned">
						<?php $last_reply = $topic->getLatestReply(); ?>

						@if(empty($last_reply))
							No replies
						@else
							<div class="ui feed pull-right">
							  <div class="event">
							    <div class="label">
							      <img src="{{ $last_reply->user->getProfileImage() }}">
							    </div>
							    <div class="content">
							      <div class="date">
							        {{ Core::getDateShort($last_reply->created_at) }}
							      </div>
							      <div class="summary">
							         {{ $last_reply->user->getName() }} posted <a href="{{ $last_reply->url }}">{{ $last_reply->name }}</a>
							      </div>
							    </div>
							  </div>
							</div>
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<!-- MOBILE ONLY -->
	<table class="ui red stackable very padded large topics table mobile only">
		<thead>
			<th>Topic</th>
		</thead>
		<tbody>
			@foreach($topics as $topic)
				<tr>
					<td>
						<a href="{{ route('plugin.adaptbb.topics.view', [ 'forum_slug' => $forum->slug, 'topic_slug' => $topic->slug ]) }}">
							<strong>{{ $topic->name }}</strong>
						</a><br />

						<small>By: <a href="{{ route('users.profile.view', [ 'username' => $topic->user->username ]) }}">{{ $topic->user->username }}</a> @ {{ Core::getDateLong($topic->created_at) }}</small>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{ $topics->links() }}
@endif

{!! Theme::partial('adaptbb.user_box') !!}
