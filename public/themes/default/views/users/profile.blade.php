<h1 class="title is-1">{{ $user->username }} Profile</h1>

<div class="columns features">
	<div class="column is-6">
	    <div class="card">
	      @if(!empty($user->profile_image))
	      	<div class="card-image has-text-centered">
	      		<img src="{{ $user->getProfileImage('medium') }}">
	      	</div>
	      @endif
	    	<div class="card-content">
				<div class="media">
					<div class="media-content">
						<p class="title is-4">
							{{ $user->getName() }}
						</p>
						<p class="subtitle is-6">
							{{ '@' . $user->username }}
						</p>
						<p class="subtitle is-6">
							<time datetime="{{ $user->created_at }}">
			          			<small>Joined: {{ Core::getDateLong($user->created_at) }}</small>
			          		</time>
						</p>
					</div>
		    	</div>
	        	<div class="content">
	          		
	        	</div>
	      	</div>
	    </div>
	</div>
</div>  