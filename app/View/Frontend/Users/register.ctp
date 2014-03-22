{{ addCrumb('Register', null) }}

<h1>Sign Up</h1>

{{ form.create('User', array('class' => 'admin-validate')) }}
	{{ form.input('username', array('class' => 'required')) }}
	<span id="username_ajax_result"></span>
    {{ form.input('password', array('type' => 'password', 'class' => 'required')) }}
    {{ form.input('password_confirm', array('type' => 'password', 'class' => 'required')) }}
    {{ form.input('email', array('class' => 'required email')) }}

    {{ form.hidden('last_reset_time', array('value' => $this->Time->format('Y-m-d H:i:s', time()) )) }}

	{% if not empty(security_questions) && !empty($security_options) %}
		{{ form.input('security_question_hidden', array(
			'options' => $security_options,
			'label' => false,
			'div' => false,
			'style' => 'display:none'
		)) }}

		{% for 1,security_questions %}
			{{ form.input('Security.' . $i . '.question', array(
				'empty' => '- choose -',
				'class' => 'required security-question',
				'options' => $security_options,
				'label' => 'Security Question ' . $i
			)) }}
			<div id="Security{{ i }}Question" style="display: none">
				{{ form.input('Security.' . $i . '.answer', array(
					'class' => 'required security-answer',
					'label' => 'Security Answer ' . $i
				)) }}
			</div>
		{% endfor %}
	{% endif %}

	{% if not empty(captcha_setting) %}
		<div id="captcha" class="input text">
			{{ captcha.form() }}
		</div>
	{% endif %}

{{ form.end(array('label' => 'Submit', 'class' => 'btn', 'id' => 'submit')) }}

{% if not empty(this->Facebook) %}
	<h1>3rd Party Signup</h1>

	{{ facebook_registration }}
{% endif %}
<div class="clearfix"></div>