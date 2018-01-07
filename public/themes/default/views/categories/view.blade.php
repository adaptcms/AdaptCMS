<h1 class="title is-1">{{ $category->name }}</h1>

@if(empty($posts['posts_with_data']))
	<p>No posts found in this category.</p>
@else
	<div class="columns features">
		@foreach($posts['posts_with_data'] as $post)
			<?php $image = $post['post']->getFieldValue($post['post_data'], 'image') ?>
			<div class="column is-6">
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

			      	<footer class="card-footer">
			      		<a href="{{ route('posts.view', [ 'slug' => $post['post']->slug ]) }}" class="card-footer-item">Learn More</a>
			      	</footer>
			    </div>
			</div>
		@endforeach
	</div>  

	{{ $posts['posts']->links() }}
@endif