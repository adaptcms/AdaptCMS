<div id="media-modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<i class="fa fa-times fa-lg close" ng-click="toggleModal($event, 'close')"></i>
			    <h4 class="modal-title">Add Images to Library</h4>
			</div>
			<div class="modal-body">
				<ul class="thumbnails" ng-show="getImages().results">
					<li class="img-container file_info col-lg-4 center" ng-repeat="image in getImages().results" ng-class="{ active: hasReachedLimit() && !isSelectedImage('', image.id) }">
						<img ng-src="{{ path }}{{ image.dir }}{{ image.filename }}" ng-show="image.exists" class="thumbnail" ng-click="addImage('', image)">
						<img ng-src="{{ path }}img/no_file.png" ng-show="!image.exists" class="thumbnail" ng-click="addImage('', image)">

						<h4>
							{{ image.label }}

							<input type="checkbox" ng-disabled="hasReachedLimit() && !isSelectedImage('', image.id)" ng-checked="isSelectedImage('', image.id)" ng-click="addImage('', image)" class="file" ng-value="image.id">
						</h4>

						<em>
							Uploaded {{ image.timestamp | date:'MMMM d yyyy' }}
						</em>
					</li>
				</ul>
				<p ng-show="!getImages().results">
					No Images to Select
				</p>
				<div class="clearfix"></div>

				<div ng-show="getImages().results != ''">
					<div ng-show="getImages().paginator.pageCount > 1" class="pagination pull-left" style="margin: 0;">
						<ul class="pagination">
							<li ng-class="(getImages().paginator.prevPage === false)? 'disabled' : 'prev'">
								<a ng-hide="getImages().paginator.prevPage === false" href="{{ getUrlPath() }}/page:{{ getImages().paginator.page - 1 }}" ng-click="paginator($event)" rel="prev">«</a>
								<a ng-hide="getImages().paginator.prevPage !== false">«</a>
							</li>
							</li>
							<li ng-repeat="page in getImages().pages" ng-class="(getImages().paginator.page == page)? ' active' : ''">
								<a href="{{ getUrlPath() }}/page:{{ page }}" ng-click="paginator($event)">{{ page }}</a>
							</li>
							<li ng-class="(getImages().paginator.nextPage === false)? 'disabled' : 'next'">
								<a ng-hide="getImages().paginator.nextPage === false" href="{{ getUrlPath() }}/page:{{ getImages().paginator.page + 1 }}" ng-click="paginator($event)" rel="next">»</a>
								<a ng-hide="getImages().paginator.nextPage !== false">»</a>
							</li>
						</ul>
					</div>
					<div class="pagination-details pull-right" style="margin-top: 10px;">
						Showing records <strong>{{ getImages().start }}-{{ getImages().end }}</strong> out of <strong>{{ getImages().count }}</strong> total
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="clearfix"></div>
				<div class="sort-options pull-left col-lg-10 no-pad-l">
					<div ng-show="!getLimit('') && getLimit('') != '1'" style="display: inline;">
						<?= $this->Form->input('select_all', array('type' => 'checkbox', 'div' => false, 'ng-click' => "selectMultipleImages('all')")) ?>
						<?= $this->Form->input('select_none', array('type' => 'checkbox', 'div' => false, 'ng-click' => "selectMultipleImages('none')")) ?>
					</div>

					<?= $this->Form->input('sort_by', array(
						'type' => 'select',
						'empty' => '- Sort Images -',
						'div' => false,
						'label' => false,
						'class' => 'btn-success btn',
						'id' => 'MediaSortBy',
						'ng-model' => 'sort_by',
						'ng-options' => 'item.id as item.label for item in sort_by_options',
						'ng-change' => "sortBy()"
					)) ?>
					<?= $this->Form->input('sort_direction', array(
						'type' => 'select',
						'empty' => '- sort direction -',
						'div' => false,
						'label' => false,
						'style' => 'display: none',
						'class' => 'btn-success btn',
						'id' => 'MediaSortDirection',
						'ng-model' => 'sort_direction',
						'ng-options' => 'item.id as item.label for item in sort_direction_options',
						'ng-change' => "sortDirection()"
					)) ?>
					<?= $this->Form->button('<i class="fa fa-times"></i>', array(
						'escape' => false,
						'class' => 'btn btn-danger reset-sorting',
						'style' => 'display: none',
						'title' => 'Reset Sorting',
						'ng-click' => 'resetSorting($event)'
					)) ?>
				</div>
				<div class="pull-right">
					<button class="btn btn-danger" ng-click="toggleModal($event, 'close')">Close</button>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>