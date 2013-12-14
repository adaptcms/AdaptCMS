{{ addCrumb('Login', null) }}

<h1>Login</h1>

{{ form.create() }}
	{{ form.input('username') }}
	{{ form.input('password') }}
{{ form.end(array('class' => 'btn', 'label' => 'Login')) }}

<a href="{{ url('register') }}">Don't have an account? Register now!</a><br />
<a href="{{ url('user_forgot_password') }}">Forgot Password?</a><br />

{% if not empty(this->Facebook) %}
	<h1>3rd Party Login</h1>

	<p>
		{{ facebook.login }}
	</p>
	<p>
		{% if current_user('login_type') && current_user('login_type') == 'facebook' %}
			{{ facebook.logout }}
		{% endif %}
	</p>
{% endif %}

<div class="clearfix"></div>