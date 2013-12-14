{{ addCrumb('Login', url('login')) }}
{{ addCrumb('Activate Account', null) }}

<h2>
    Activate Account
</h2>

{{ form.create() }}
	{{ form.input('username', array(
		'type' => 'text',
		'class' => 'required'
	)) }}
    {{ form.input('activate_code', array(
    	'type' => 'text', 
    	'class' => 'required'
    )) }}

{{ form.end('Submit') }}