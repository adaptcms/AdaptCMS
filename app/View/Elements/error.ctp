<div id="{% if not empty(id) %}{{ id }}{% else %}flashMessage{% endif %}" class="alert alert-danger"{% if not empty(hidden) %} style="display: none;"{% endif %}>
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error</strong>
	{% if not empty(message) %}
		{{ message }}
	{% endif %}
</div>