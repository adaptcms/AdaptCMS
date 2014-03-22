{% if empty(model) %}
	{{ set model = $this->Paginator->defaultModel() }}
{% endif %}
{{ set numbers = $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentClass' => 'active paginator', 'first' => '1', 'model' => $model )) }}

{% if not empty(numbers) %}
	{% if not empty(page) %}
		{{ paginator.options(array('url' => array(
			'model' => $model,
			$page
		))) }}
	{% endif %}

	<div class="pagination">
		<ul class="pagination">
			{{ paginator.prev('«', array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a')) }}
			{{ paginator.numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1, 'model' => $model, 'ng-click' => 'paginator($event)')) }}
			{{ paginator.next('»', array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a')) }}
		</ul>
	</div>

	{{ paginator.counter('Showing records <strong>{:start}-{:end}</strong> out of <strong>{:count}</strong> total', array('escape' => false, 'model' => $model)) }}
{% endif %}