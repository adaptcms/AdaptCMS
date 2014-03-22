{{ tinymce.simple }}

{{ addCrumb($page_name, null) }}
{{ setTitle($page_name) }}

{{ form.create('ContactForm', array('class' => 'well admin-validate')) }}
	<h2>{{ page_name }}</h2>

	{{ form.input('name', array('label' => 'Your Name')) }}
	{{ form.input('email', array('label' => 'Your Email Address')) }}
	{{ form.input('message', array('label' => 'Your Message', 'rows' => 15, 'style' => 'width:500px')) }}

	{% if not empty(captcha) %}
		<div id="captcha">
			{{ captcha.form() }}
		</div>
	{% endif %}

{{ form.end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) }}