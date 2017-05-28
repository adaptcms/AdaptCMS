
<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Site Properties -->
  <title>@section('title')</title>

  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <link rel="stylesheet" type="text/css" href="/css/semantic-ui-calendar.min.css">
  
  <link rel="stylesheet" type="text/css" href="/themes/default/assets/css/main.compiled.min.css">
  
  @stack('css')
</head>
<body>
	@include('users::Users/admin-check')

	<!-- Following Menu -->
	<div class="ui large top fixed hidden menu main-menu">
	  <div class="ui container">
		  <div class="header item">
			  AdaptCMS
		  </div>
	    
	    @foreach(Core::getData('pages') as $page)
	    	@if($page->slug == 'home')
	    		<a href="{{ route('home') }}" class="{{ Request::url() == route('home') ? 'active' : '' }} item">
		    	{{ $page->name }}
	    	</a>
	    	@else
		    	<a href="{{ route('pages.view', [ 'slug' => $page->slug ]) }}" class="{{ Request::url() == route('pages.view', [ 'slug' => $page->slug ]) ? 'active' : '' }} item">
			    	{{ $page->name }}
		    	</a>
	    	@endif
	    @endforeach
	    
	    @foreach(Core::getData('categories', 'all', [], [ 'ord', 'asc' ]) as $category)
	    	<a href="{{ route('categories.view', [ 'slug' => $category->slug ]) }}" class="{{ Request::url() == route('categories.view', [ 'slug' => $category->slug ]) ? 'active' : '' }} item">
		    	{{ $category->name }}
	    	</a>
	    @endforeach
	
	    <div class="right menu">
		  @if(!Auth::user())
		      <div class="item">
		        <a href="{{ route('login') }}" class="ui button">Log in</a>
		      </div>
		      <div class="item">
		        <a href="{{ route('register') }}" class="ui primary button">Sign Up</a>
		      </div>
		  @else
		  	<div class="item">
			  	<a href="{{ route('users.profile.edit') }}" class="ui green button">Edit Profile</a>
			  	<a href="{{ Core::getDashboardUrl() }}" class="ui primary button">Dashboard</a>
		  	</div>
		  @endif
	    </div>
	  </div>
	</div>
	
	<!-- Sidebar Menu -->
	<div class="ui vertical inverted sidebar menu">
	  <a class="active item">Home</a>
	  <a class="item">Work</a>
	  <a class="item">Company</a>
	  <a class="item">Careers</a>
	  <a class="item">Login</a>
	  <a class="item">Signup</a>
	</div>
	
	
	<!-- Page Contents -->
	<div class="pusher">
      @include('partials/flash')
	  @yield('content')
	
	  <div class="ui inverted vertical footer segment">
	    <div class="ui container">
	      <div class="ui stackable inverted divided equal height stackable grid">
	        <div class="three wide column">
	          <h4 class="ui inverted header">About</h4>
	          <div class="ui inverted link list">
	            <a href="#" class="item">Sitemap</a>
	            <a href="#" class="item">Contact Us</a>
	            <a href="#" class="item">Religious Ceremonies</a>
	            <a href="#" class="item">Gazebo Plans</a>
	          </div>
	        </div>
	        <div class="three wide column">
	          <h4 class="ui inverted header">Services</h4>
	          <div class="ui inverted link list">
	            <a href="#" class="item">Banana Pre-Order</a>
	            <a href="#" class="item">DNA FAQ</a>
	            <a href="#" class="item">How To Access</a>
	            <a href="#" class="item">Favorite X-Men</a>
	          </div>
	        </div>
	        <div class="seven wide column">
	          <h4 class="ui inverted header">Footer Header</h4>
	          <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="//cdn.jsdelivr.net/ace/1.2.6/min/ace.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/string.js/3.3.3/string.min.js"></script>
  
  <script src="/js/vendor/jquery.tagsinput.min.js"></script>
  <script src="/js/vendor/semantic-ui-calendar.min.js"></script>
  <script src="/js/vendor/jquery-sortable.min.js"></script>
  
  <script src="/js/vendor/vue.min.js"></script>
  <script src="/js/vendor/vue-router.min.js"></script>
  
  @stack('js')
  
  <script src="/themes/default/assets/js/main.compiled.min.js"></script>

</body>
</html>