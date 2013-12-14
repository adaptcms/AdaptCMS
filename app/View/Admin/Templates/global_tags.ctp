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

	<h4>Current Tags</h4>

	<?= $this->Form->create('', array('ng-controller' => 'FormCtrl')) ?>
		<?= $this->Form->button('<i class="icon-plus"></i> Add Tag', array(
			'escape' => false,
			'class' => 'btn btn-info pull-right',
			'ng-click' => 'addTag($event)'
		)) ?>

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
						<input type="text" name="tags[$index][tag]" ng-model="tags[$index].tag">
					</td>
					<td>
						<label>Value</label>
						<textarea name="tags[$index][value]" ng-model="tags[$index].value" class="span10 col-lg-10" rows="8"></textarea>
					</td>
					<td>
						<?= $this->Form->button('<i class="icon-trash"></i> Remove Tag', array(
							'escape' => false,
							'class' => 'btn btn-danger',
							'ng-click' => 'removeTag($event, $index)'
						)) ?>
					</td>
				</tr>
			</tbody>
		</table>
		<div ng-show="tags == ''">
			No Tags are Currently added
		</div>
		<?= $this->Form->button('Update', array(
			'class' => 'btn btn-primary',
			'div' => false,
			'ng-click' => 'updateTags($event)',
			'ng-hide' => "tags == ''"
		)) ?>
	<?= $this->Form->end() ?>
</div>