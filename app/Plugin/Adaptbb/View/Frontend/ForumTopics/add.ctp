{{ tinymce.simple }}

{{ setTitle('Forums - ' . $forum['title'] . ' :: Add Topic') }}

{{ addCrumb('Forums', url('adaptbb_forums')) }}
{{ addCrumb($forum['title'] . ' Forum', url('adaptbb_view_forum', $forum['slug'])) }}
{{ addCrumb('New Topic', null) }}

<h2>
	{{ forum['title'] }} Forum - Add Topic
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
    {% if not empty(topic_type) %}
        {{ form.input('topic_type', array(
            'options' => $topic_type
        )) }}
	{% else %}
        {{ form.hidden('topic_type', array('value' => 'topic' )) }}
	{% endif %}
    <!--/nocache-->

	{{ form.hidden('forum_id', array('value' => $forum['id'] )) }}

	<div class="clearfix"></div>

{{ form.end(array(
	'label' => 'Submit Topic',
	'class' => 'btn btn-primary'
)) }}