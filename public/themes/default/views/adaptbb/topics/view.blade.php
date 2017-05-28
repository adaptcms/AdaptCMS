	<div id="topic">
			<h1>Community Forums</h1>
			<h2>Forum: <a href="{{ route('plugin.adaptbb.forums.view', [ 'slug' => $forum->slug ]) }}">{{ $forum->name }}</a></h2>
			<h3>{{ $topic->name }}</h3>

			<div class="ui segment right aligned">
				<a href="{{ route('plugin.adaptbb.forums.view', [ 'slug' => $forum->slug ]) }}" class="ui right labeled icon button primary large">
					Back to {{ $forum->name }}
					<i class="list icon"></i>
				</a>
			</div>

			{{ $replies->links() }}

			{!! Theme::partial('adaptbb.post', [ 'post' => $topic ]) !!}

			@if($replies->count())
				<div class="ui comments">
					<h3 class="ui dividing header">Replies</h3>

					@foreach($replies as $reply)
						{!! Theme::partial('adaptbb.post', [ 'post' => $reply ]) !!}
					@endforeach
				</div>
			@endif

			{{ $replies->links() }}

            <div v-if="replies.length" class="ui comments">
        <div class="comment" v-for="reply in replies">
            <a class="avatar">
    <img :src="reply.profile_image">
            </a>
            <div class="content">
         <a class="author" :href="reply.profile_url">{@ reply.profile_username @}</a>
          <div class="metadata">
    <span class="date">{@ reply.date @}</span>
          </div>
          <div class="text" v-html="reply.message">
                          </div>
          <div class="actions">
                <a href="" class="reply" @click.prevent="triggerReply()">Reply</a>
              </div>
            </div>
          </div>
            </div>

			<div id="reply-box" class="ui segment padded hidden">
				<form class="ui reply form" @submit.prevent="submitReply()">
				    <div class="field">
					    <input type="hidden" class="reply-name" v-model="name" value="Re: {{ $topic->name }}">
						<textarea v-model="message"></textarea>
				    </div>
				    <button type="submit" class="ui right labeled icon blue submit button">
				      <i class="icon edit"></i> Add Reply
				    </button>
				  </form>
			</div>

			{!! Theme::partial('adaptbb.user_box') !!}
	</div>

	<style>
	.ui.button.comment {
		width: 150px!important;
	}
	</style>

	{{ Theme::asset()->add('core-script', '/assets/modules/adaptbb/js/topics.view.js') }}
