<div class="search-results" id="module-{{ module_id }}">
	<h2 class="pull-left">
		Search for
		<small>{{ q }}</small>
		in {{ module_name }}
	</h2>
			<span class="pull-right" style="margin-top: 20px">
				<strong>
					{{ count }}
				</strong>
				Total Result(s)
			</span>

	<div class="clearfix"></div>

	<div class="well">
		{% if not empty(results) %}
		<ul>
			{% loop result in results %}
				{{ partial($element, array('data' => $result)) }}
			{% endloop %}
		</ul>
		{% else %}
		No Results
		{% endif %}
	</div>

	{% if not empty(results) %}
		{{ partial('pagination', array('model' => $model_name)) }}
	{% endif %}
</div>