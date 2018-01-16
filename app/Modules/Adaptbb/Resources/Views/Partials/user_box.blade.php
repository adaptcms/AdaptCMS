<div class="ui segment right aligned">
    @if(!Auth::user())
      <a href="{{ route('login') }}" class="ui button primary right labeled icon">
          Login 
          <i class="sign in icon"></i>
      </a>
      <a href="{{ route('register') }}" class="ui button green right labeled icon">
          Register 
          <i class="wpforms icon"></i>
      </a>
    @else
        Welcome back, {{ Auth::user()->first_name }}! <a href="{{ route('logout') }}">Logout</a>
    @endif
</div>