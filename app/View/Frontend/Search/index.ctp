<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('vendor/angular-sanitize.min') ?>

<?php $this->Html->addCrumb('Search', null) ?>

<div ng-app="search">
	<div ng-controller="SearchCtrl">
		<?= $this->Form->create('Search', array(
			'url' => array(
				'action' => 'index',
				'controller' => 'search',
				'plugin' => false,
				'admin' => false
			),
			'class' => 'search-results'
		)) ?>
			<?= $this->Form->input('q', array(
				'label' => false,
				'placeholder' => 'Enter Keyword...',
				'div' => false,
				'style' => 'margin-top: 0',
				'ng-model' => 'q',
				'ng-init' => (!empty($this->request->data['Search']['q']) ? 'q = "' . $this->request->data['Search']['q'] . '"' : '')
			)) ?>
			<?= $this->Form->input('module', array(
				'empty' => 'All',
				'options' => ( !empty($modules) ? $modules : array() ),
				'label' => false,
				'div' => false,
				'class' => 'span2',
				'style' => 'margin-top: 0',
				'ng-model' => 'module',
				'ng-init' => (!empty($this->request->data['Search']['module']) ? 'module = "' . $this->request->data['Search']['module'] . '"' : '')
			)) ?>

			<?= $this->Form->button('Search', array(
				'class' => 'btn btn-primary',
				'div' => false,
				'ng-click' => 'search($event)'
			)) ?>
		<?= $this->Form->end() ?>

		<div id="results">
			<div class="search-results clearfix" ng-repeat="module in modules">
				<h2 class="pull-left">
					Search for
					<small>{{ module.q }}</small>
					in {{ module.name }}
				</h2>
				<span class="pull-right" style="margin-top: 20px">
					<strong>
						{{ module.count }}
					</strong>
					Total Result(s)
				</span>
				<div class="clearfix"></div>

				<div class="well">
					<p ng-hide="module.results != ''">
						No Results
					</p>
					<div ng-show="module.results != ''" ng-bind-html="module.results">
					</div>
				</div>

				<div ng-show="module.results != '' && module.paginator.pageCount > 1" class="pagination pull-left">
					<ul class="pagination">
						<li ng-class="(module.paginator.prevPage === false)? 'disabled' : 'prev'">
							<a ng-hide="module.paginator.prevPage === false" href="{{ module.path }}/model:{{ module.model }}/page:{{ module.paginator.page - 1 }}" ng-click="paginator($event)" rel="prev">«</a>
							<a ng-hide="module.paginator.prevPage !== false">«</a>
						</li>
						</li>
						<li ng-repeat="page in module.pages" ng-class="(module.paginator.page == page)? ' active' : ''">
							<a href="{{ module.path }}/model:{{ module.model }}/page:{{ page }}" ng-click="paginator($event)">{{ page }}</a>
						</li>
						<li ng-class="(module.paginator.nextPage === false)? 'disabled' : 'next'">
							<a ng-hide="module.paginator.nextPage === false" href="{{ module.path }}/model:{{ module.model }}/page:{{ module.paginator.page + 1 }}" ng-click="paginator($event)" rel="next">»</a>
							<a ng-hide="module.paginator.nextPage !== false">»</a>
						</li>
					</ul>
				</div>
				<div ng-show="module.results != ''" class="pagination-details pull-right">
					Showing records <strong>{{ module.start }}-{{ module.end }}</strong> out of <strong>{{ module.count }}</strong> total
				</div>
			</div>
		</div>
	</div>
</div>