<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Global Template Tags', null) ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>

<h2>Global Template Tags</h2>

<div class="well" ng-app="tags">
	<p>
		Below you can add global template tags. This allows you to call the tag in any template on your site and the value you enter, will be the one that is displayed.
	</p>
	<p ng-non-bindable>
		Keep in mind, to call a template tag - {{ tag_name }}
	</p>

	<?= $this->Form->create('', array('ng-controller' => 'FormCtrl', 'ng-submit' => 'updateTags($event)')) ?>
		<h4 class="pull-left">Current Tags</h4>

		<div class="btn-group pull-right">
			<?= $this->Form->button('Update <i class="fa fa-save"></i>', array(
				'class' => 'btn btn-primary',
				'div' => false,
				'ng-click' => 'updateTags($event)',
				'ng-hide' => "tags == ''"
			)) ?>
			<?= $this->Form->button('Add Tag <i class="fa fa-plus"></i>', array(
				'escape' => false,
				'class' => 'btn btn-info',
				'ng-click' => 'addTag($event)'
			)) ?>
		</div>
		<div class="clearfix"></div>

		<div class="table-responsive">
			<table class="table" ng-hide="tags == ''">
				<thead>
					<th></th>
					<th></th>
					<th></th>
				</thead>
				<tbody>
					<tr ng-repeat="tag in tags" ng-hide="tag.enabled == false">
						<td>
							<label>Tag Name</label>
							<input type="text" class="form-control" name="tags[$index][tag]" ng-model="tags[$index].tag">
						</td>
						<td>
							<label>Value</label>
							<textarea name="tags[$index][value]" ng-model="tags[$index].value" class="col-lg-10 form-control" rows="8"></textarea>
						</td>
						<td>
							<?= $this->Form->button('<i class="fa fa-trash-o"></i> Remove Tag', array(
								'escape' => false,
								'class' => 'btn btn-danger pull-right',
								'ng-click' => 'removeTag($event, $index)'
							)) ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div ng-show="tags == ''">
			No Tags are Currently added
		</div>
		<?= $this->Form->button('Update <i class="fa fa-save"></i>', array(
			'class' => 'btn btn-primary',
			'div' => false,
			'ng-click' => 'updateTags($event)',
			'ng-hide' => "tags == ''"
		)) ?>
	<?= $this->Form->end() ?>
</div>