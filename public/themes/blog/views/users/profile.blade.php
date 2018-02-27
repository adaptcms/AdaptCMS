<div class="ui red very padded segment">
	<div class="ten columns centered">
		<h1>Profile</h1>

    	<div class="ui card">
    		@if(!empty($user->profile_image))
    			<div class="image">
					<img src="{{ $user->getProfileImage('medium') }}">
				</div>
    		@endif
		  <div class="content">
		    <a class="header">{{ $user->getName() }}</a>
		    <div class="meta">
		      <span class="date">Joined in {{ date('F Y', strtotime($user->created_at)) }}</span>
		    </div>
		  </div>
		  <div class="extra content">
		    <a>
		      <i class="user icon"></i>
		      {{ $user->username }}
		    </a>
		  </div>
		</div>
  </div>
</div>
