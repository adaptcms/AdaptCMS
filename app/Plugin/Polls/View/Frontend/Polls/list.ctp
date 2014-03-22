{{ addCrumb('Polls', null) }}

{{ setTitle('Polls List') }}

<h1>Polls Archive</h1>

{% if empty(polls) %}
	<p>
		Sorry, but there are no polls available at this time. Please check back soon!
	</p>
{% else %}
	<ul class="list-unstyled clearfix">
		{% loop poll in polls %}
			<li class="col-lg-7" style="margin-bottom: 15px;">
				{% if not empty(poll['Poll']['can_vote']) %}
					{{ partial('Polls.poll_vote', array('data' => $poll)) }}
				{% else %}
					{{ partial('Polls.poll_vote_results', array('data' => $poll)) }}
				{% endif %}
			</li>
		{% endloop %}
	</ul>

	{{ partial('pagination') }}
{% endif %}