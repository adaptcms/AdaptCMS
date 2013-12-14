{{ addCrumb('Login', url('login')) }}
{{ addCrumb('Reset Password', null) }}

{{ js('users.reset_password') }}

<h2>
    Reset Password
</h2>

<p>The {{ reset_time }} day limit has been reached since your last password change, please enter a new one below</p>

{{ form.create('User', array('class' => 'admin-validate')) }}

    {{ form.input('username', array('type' => 'text', 'class' => 'required')) }}

    {{ form.input('password_current', array(
            'type' => 'password', 
            'label' => 'Current Password',
            'class' => 'required'
    )) }}

    {{ form.input('password', array(
            'type' => 'password',
            'label' => 'New Password',
            'class' => 'required'
    )) }}
    {{ form.input('password_confirm', array(
            'type' => 'password', 
            'class' => 'required'
    )) }}

    <label>Captcha</label>

    <div id="captcha">
        {{ captcha.form('data[User][captcha]') }}
    </div>
{{ form.end('Submit') }}