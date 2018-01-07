<div class="columns features">
	<?php $image = $post['post']->getFieldValue($post['post_data'], 'image') ?>
	<div class="column is-12">
	    <div class="card">
	      @if(!empty($image))
	      	<div class="card-image has-text-centered">
	      		<img src="{{ $image }}">
	      	</div>
	      @endif
	    	<div class="card-content">
				<div class="media">
					@if(!empty($post['user']->profile_image))
						<div class="media-left">
							<figure class="image is-48x48">
								<img src="{{ $post['user']->getProfileImage('small') }}" alt="{{ $post['user']->username }} profile photo">
							</figure>
						</div>
					@endif
					<div class="media-content">
						<p class="title is-4">
							<a href="{{ route('users.profile.view', [ 'username' => $post['user']->username ]) }}">
								{{ $post['user']->username }}
							</a>
						</p>
						<p class="subtitle is-6">
							<time datetime="{{ $post['post']->created_at }}">
			          			<small>Posted: {{ Core::getDateLong($post['post']->created_at) }}</small>
			          		</time>
						</p>
					</div>
		    	</div>
	        	<div class="content">
	          		<h4><a href="{{ route('posts.view', [ 'slug' => $post['post']->slug ]) }}" class="header">{{ $post['post']->name }}</a></h4>
	          		<p>{!! str_limit($post['post']->getFieldValue($post['post_data'], 'body'), 300) !!}</p>

	          		@if(!empty($post['tags']))
						@foreach($post['tags'] as $tag)
							<span class="tag"><a href="{{ route('tags.view', [ 'slug' => $tag->slug ]) }}">{{ $tag->name }}</a></span>
						@endforeach
					@endif
	        	</div>
	      	</div>
	    </div>
	</div>
</div>  

<div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

var disqus_config = function () {
this.page.url = '{{ Request::url() }}';  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = '{{ env('APP_KEY') }}_{{ $post['post']->id }}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};

(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = '//adaptcms.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>