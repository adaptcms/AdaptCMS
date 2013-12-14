{{ tinymce }}
{{ js('bootstrap-typeahead.js') }}

{{ addCrumb('Profile', url('user_profile', current_user('username'))) }}
{{ addCrumb('Messages', url('messages_index')) }}
{{ addCrumb('Send Message', null) }}

<h1>Send Message</h1>

<a href="{{ url('messages_index') }}" class="btn btn-primary pull-right">« Back to Messages</a>
<div class="clearfix"></div>

{{ form.create('', array('class' => 'well span12 no-marg-left admin-validate')) }}
	
	{{ form.input('recipient', array(
	        'data-provide' => 'typeahead', 
	        'data-source' => '[]', 
	        'autocomplete'=>'off',
            'value' => !empty($this->params['pass'][0]) ? $this->params['pass'][0] : ''
	)) }}
	{{ form.input('title', array(
		'label' => 'Subject',
		'class' => 'required'
	)) }}
	{{ form.input('message', array(
		'class' => 'required span7',
		'style' => 'height: 100%'
	)) }}

	{{ form.hidden('parent_id', array('value' => 0)) }}
	{{ form.hidden('receiver_user_id') }}

	{{ form.submit('Send Message', array(
		'class' => 'btn btn-info',
		'style' => 'margin-top: 10px'
	)) }}
{{ form.end() }}