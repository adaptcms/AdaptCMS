{{ tinymce.simple }}

{{ setTitle('Forums - ' . $topic['Forum']['title'] . ' :: Edit Topic') }}

{{ addCrumb('Forums', url('adaptbb_forums')) }}
{{ addCrumb($topic['Forum']['title'] . ' Forum', url('adaptbb_view_forum', $topic['Forum']['slug'])) }}
{{ addCrumb($topic['ForumTopic']['subject'], url('adaptbb_view_topic', $topic)) }}
{{ addCrumb('Edit Topic', null) }}

<h2>
	{{ topic['Forum']['title'] }} Forum - Edit Topic
</h2>

{{ form.create('ForumTopic', array('class' => 'well admin-validate')) }}
	
	{{ form.input('subject', array(
		'type' => 'text', 
		'class' => 'required'
	)) }}
	{{ form.input('content', array(
		'type' => 'textarea',
		'class' => 'required span8',
		'style' => 'height: 100%'
	)) }}

    <!--nocache-->
	{% if $this->Admin->hasPermission($permissions['related']['forum_topics']['change_status']) %}
		{{ form.input('status', array(
			'options' => array(
				'Closed',
				'Open'
			)
		)) }}
	{% endif %}
    {% if not empty(topic_type) %}
        {{ form.input('topic_type', array(
            'options' => $topic_type
        )) }}
	{% else %}
        {{ form.hidden('topic_type', array('value' => 'topic' )) }}
	{% endif %}
    <!--/nocache-->

	{{ form.hidden('id', array('value' => $topic['ForumTopic']['id'] )) }}

	<div class="clearfix"></div>

{{ form.end(array(
	'label' => 'Update Topic',
	'class' => 'btn btn-primary'
)) }}