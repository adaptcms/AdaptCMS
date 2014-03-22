{% if not empty(data) %}
	{% if not empty(data['Poll']['can_vote']) && not empty(permissions['related']) %}
        {{ partial('Polls.poll_vote', array('data' => $data)) }}
	{% else %}
        {{ partial('Polls.poll_vote_results', array('data' => $data)) }}
	{% endif %}
{% endif %}