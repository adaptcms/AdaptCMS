<h1 class="text-color-black">{{ date('F Y', $time) }}</h1>

<div class="ui grid">
	<div class="ui divided items">

		@if(empty($posts['posts_with_data']))
			<p>No posts for this category.</p>
		@else
			@foreach($posts['posts_with_data'] as $post)
			  <div class="item">
			    <div class="image">
				  <?php $image = $post['post']->getFieldValue($post['post_data'], 'image') ?>

				  @if(!empty($image))
			      	<img src="{{ $image }}">
			      @endif
			    </div>
			    <div class="content">
			      <a href="{{ route('posts.view', [ 'slug' => $post['post']->slug ]) }}" class="header">{{ $post['post']->name }}</a>
			      <div class="meta">
			        <span class="cinema"><small>Posted: {{ Core::getDateLong($post['post']->created_at) }}</small></span>
			      </div>
			      <div class="description text-color-black">
			        {!! str_limit($post['post']->getFieldValue($post['post_data'], 'body'), 300) !!}
			      </div>
			      <div class="extra">
			        <a href="{{ route('posts.view', [ 'slug' => $post['post']->slug ]) }}" class="ui right floated primary button">
			          Read More
			          <i class="right chevron icon"></i>
			        </a>
			        @if(!empty($post['tags']))
			        	@foreach($post['tags'] as $tag)
				        	<div class="ui label"><a href="{{ route('tags.view', [ 'slug' => $tag->slug ]) }}">{{ $tag->name }}</a></div>
				        @endforeach
			        @endif
			      </div>
			    </div>
			  </div>
			@endforeach

			{{ $posts['paginated']->links() }}
		@endif
	</div>
</div>