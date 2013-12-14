{% if $this->Admin->hasPermission($permissions['related']['comments']['ajax_post']) %}
    {% if empty(article['Article']['settings']['comments_status']) || $article['Article']['settings']['comments_status'] == 'open' %}
        {{ form.create('Comment', array('class' => 'PostComment admin-validate')) }}

			{{ form.input('author_name', array(
				'label' => 'Your Name',
				'class' => 'author_name',
				'value' => ($this->Session->check('Comment.author_name') ? $this->Session->read('Comment.author_name') : '')
			)) }}
			{{ form.input('author_email', array(
				'label' => 'Your Email Address',
				'class' => 'email author_email',
				'value' => ($this->Session->check('Comment.author_email') ? $this->Session->read('Comment.author_email') : ($this->Session->check('Auth.User.email') ? $this->Session->read('Auth.User.email') : ''))
			)) }}
			{{ form.input('author_website', array(
				'label' => 'Your Website',
				'class' => 'url author_website',
				'placeholder' => 'http://',
				'value' => ($this->Session->check('Comment.author_website') ? $this->Session->read('Comment.author_website') : '')
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
					{{ form.hidden('ModuleValue.' . $index . '.module_id', array('value' => $article['User']['id'])) }}
					{{ form.hidden('ModuleValue.' . $index . '.module_name', array('value' => 'comment')) }}

					{% if not empty(field['ModuleValue'][0]['id']) %}
						{{ form.hidden('ModuleValue.' . $index . '.id', array('value' => $field['ModuleValue'][0]['id'])) }}
					{% endif %}
				{% endloop %}
			{% endif %}

            {{ form.input('comment_text', array(
                'class' => 'span6',
                'div' => array(
                    'style' => 'margin-bottom: 10px;margin-top: 10px'
                ),
                'label' => false,
                'placeholder' => 'Enter in your comment...'
            )) }}

            {% if not empty(article['Article']['id']) %}
                {{ form.hidden('article_id', array(
                    'value' => $article['Article']['id']
                )) }}
            {% endif %}

            {% if not empty(captcha_setting) %}
                <div id="captcha">
                    {{ captcha.form() }}
                </div>
            {% endif %}

            {{ form.button('Post Comment', array(
                'type' => 'button',
                'class' => 'btn submit-comment'
            )) }}

        {{ form.end() }}
	{% else %}
		<p>Sorry, posting comments is disabled for this article.</p>
    {% endif %}
{% elseif !$this->Session->check('Auth.User.username') %}
    Please <a href="{{ url('login') }}">login</a> or <a href="{{ url('register') }}">register</a> in order to post a comment.
{% endif %}