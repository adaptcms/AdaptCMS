@foreach($posts['posts_with_data'] as $post)
	<div class="blog-post">
	    <h2 class="blog-post-title">
	    	<a href="{{ route('posts.view', [ 'slug' => $post['post']->slug ]) }}">{{ $post['post']->name }}</a>
	    </h2>
	    
	    <p class="blog-post-meta">
	    	{{ Core::getDateLong($post['post']->created_at) }} by 
	    	<a href="{{ route('users.profile.view', [ 'username' => $post['post']->user->username ]) }}">
	    		{{ $post['post']->user->username }}
	    	</a>
	    </p>
	
	    {!! $post['post']->getFieldValue($post['post_data'], 'blog-content') !!}
	  </div><!-- /.blog-post -->
@endforeach

{{ $posts['posts']->links() }}