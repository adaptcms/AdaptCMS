{{ tinymce }}

{{ addCrumb('Profile', url('user_profile', current_user('username'))) }}
{{ addCrumb('Edit', null) }}

<h1 class="pull-left">Edit Account</h1>

<a class="pull-right btn btn-info" href="{{ url('user_profile', current_user('username')) }}">
	<i class="icon-chevron-left"></i> Return to Profile
</a>
<div class="clearfix"></div>

{{ form.create('', array('class' => 'admin-validate well', 'type' => 'file')) }}

	{{ form.input('username', array(
		'class' => 'required',
		'disabled'
	)) }}
	{{ form.input('password', array(
		'type' => 'password',
		'label' => 'New Password?',
		'value' => '',
        'required' => false
	)) }}
	{{ form.input('password_confirm', array(
		'type' => 'password',
		'value' => ''
	)) }}
	{{ form.input('email', array(
		'type' => 'text',
		'class' => 'required email'
	)) }}

	{{ form.hidden('id') }}

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

	{{ form.input('theme_id', array(
	    'label' => 'Theme',
	    'empty' => '- Choose Theme -'
	)) }}

	{{ form.input('User.settings.time_zone', array(
	    'label' => 'Timezone',
	    'empty' => '- Choose -',
	    'options' => $timezones
	)) }}

	{% if not empty(settings['avatar']) %}
	    <h4>Current Avatar</h4>

	    {{ image(
	        '/uploads/avatars/' . $this->request->data['User']['settings']['avatar'],
	        array('class' => 'thumbnail col-lg-2')
	    ) }}
	    {{ form.hidden('User.settings.old_avatar', array(
	        'value' => $this->request->data['User']['settings']['avatar']
	    )) }}
	    <div class="clearfix"></div>
	{% endif %}

	{{ form.input('User.settings.avatar', array(
	    'label' => 'Avatar',
	    'type' => 'file'
	)) }}

	{% if not empty(fields) %}
		{% loop field in fields %}
		    {{ partial('FieldTypes/' . $field['FieldType']['slug'], array(
		        'model' => 'ModuleValue',
		        'key' => $index,
		        'field' => $field,
		        'icon' => !empty($field['Field']['description']) ?
		        "<i class='icon icon-question-sign field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;" : ''
		    )) }}
		    {{ form.hidden('ModuleValue.' . $index . '.field_id', array('value' => $field['Field']['id'])) }}
		    {{ form.hidden('ModuleValue.' . $index . '.module_id', array('value' => $this->request->data['User']['id'])) }}
		    {{ form.hidden('ModuleValue.' . $index . '.module_name', array('value' => 'user')) }}

		    {% if not empty(field['ModuleValue'][0]['id']) %}
		        {{ form.hidden('ModuleValue.' . $index . '.id', array('value' => $field['ModuleValue'][0]['id'])) }}
		    {% endif %}
		{% endloop %}
	{% endif %}

{{ form.end(array('label' => 'Submit', 'class' => 'btn btn-primary')) }}