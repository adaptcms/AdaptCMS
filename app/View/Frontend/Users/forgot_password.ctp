{{ addCrumb('Login', url('login')) }}
{{ addCrumb('Forgot Password', null) }}

<h2>
    Forgot Password
</h2>

{% if not empty(activate) %}
    <p>Check your email and click on the link - from there you will enter in a new password and can then login into your account.</p>
{% else %}
	<p>Please enter your e-mail address or username and a link will be sent to you. Follow those instructions to change your password.</p>

    {{ form.create('', array('class' => 'admin-validate')) }}

		{{ form.input('username', array(
            'required' => false
        )) }}

        <h4>OR</h4>

		{{ form.input('email', array(
            'class' => 'email',
            'required' => false
        )) }}

        <label>Captcha</label>

        <div id="captcha">
            {{ captcha.form('data[User][captcha]') }}
        </div>
	{{ form.end('Submit') }}
{% endif %}