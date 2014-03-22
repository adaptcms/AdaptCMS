<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Categories', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Category', null) ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li<?= (!empty($this->params['named']) ? "" : " class='active'") ?>>
		<a href="#main" data-toggle="tab">Edit Category</a>
	</li>
	<li>
		<a href="#permissions" data-toggle="tab">Permissions</a>
	</li>
	<?php if (!empty($articles)): ?>
		<li<?= (!empty($this->params['named']) ? " class='active'" : "") ?>>
			<a href="#articles" data-toggle="tab">Related Articles</a>
		</li>
	<?php endif ?>
	<?php if (!empty($fields)): ?>
		<li>
			<a href="#fields" data-toggle="tab">Related Fields</a>
		</li>
	<?php endif ?>
	<div class="right hidden-xs">
	    <?= $this->Html->link(
	        '<i class="fa fa-chevron-left"></i> Return to Index',
	        array('action' => 'index'),
	        array('class' => 'btn btn-info', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="fa fa-trash-o"></i> Delete',
	        array('action' => 'delete', $this->request->data['Category']['id'], $this->request->data['Category']['title']),
	        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this category?')"));
	    ?>
	</div>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane <?= (!empty($this->params['named']) ? "" : "fade active in") ?>" id="main">
		<?= $this->Form->create('Category', array('class' => 'well admin-validate')) ?>
			<h2>Edit Category</h2>

			<?= $this->Form->input('title', array(
				'type' => 'text', 
				'class' => 'required'
			)) ?>
			<?= $this->Form->hidden('old_title', array(
				'value' => $this->request->data['Category']['title']
			)) ?>

			<?= $this->Form->hidden('id') ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	</div>
	<div class="tab-pane" id="permissions">
		<?= $this->Form->create('Category', array('class' => 'well admin-validate')) ?>
			<h2>Posting Permissions</h2>
			<p class="col-lg-6 span6 no-marg-left">
				If you disallow access to this category to a role type, any user with that role will not be allowed to add/edit or delete articles for that category.
			</p>

			<table class="table">
				<thead>
					<th>Role</th>
					<th>Frontend Access (Any/Own)</th>
					<th>Backend List (Any/Own)</th>
					<th>Add</th>
					<th>Edit (Any/Own)</th>
					<th>Delete (Any/Own)</th>
					<th>Restore (Any/Own)</th>
				</thead>
				<tbody>
					<?php foreach($roles as $key => $role): ?>
						<tr>
							<td>
								<?= $role['name'] ?>
							</td>
							<td>
								<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.view.any', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'checked' => ($role['view']['any'] == 1 ? true : false)
								)) ?>
								<?php if ($role['view']['own'] != 2): ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.view.own', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['view']['own'] == 1 ? true : false)
									)) ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($role['admin_index']['has_access']): ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_index.any', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_index']['any'] == 1 ? true : false)
									)) ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_index.own', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_index']['own'] == 1 ? true : false)
									)) ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($role['admin_add']['has_access']): ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_add.any', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_add']['any'] == 1 ? true : false)
									)) ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($role['admin_edit']['has_access']): ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_edit.any', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_edit']['any'] == 1 ? true : false)
									)) ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_edit.own', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_edit']['own'] == 1 ? true : false)
									)) ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($role['admin_delete']['has_access']): ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_delete.any', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_delete']['any'] == 1 ? true : false)
									)) ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_delete.own', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_delete']['own'] == 1 ? true : false)
									)) ?>
								<?php endif ?>
							</td>
							<td>
								<?php if ($role['admin_restore']['has_access']): ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_restore.any', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_restore']['any'] == 1 ? true : false)
									)) ?>
									<?= $this->Form->input('Category.settings.permissions.' . $role['id'] . '.admin_restore.own', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'checked' => ($role['admin_restore']['own'] == 1 ? true : false)
									)) ?>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
			<?= $this->Form->hidden('id') ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	</div>
	<?php if (!empty($articles)): ?>
		<div class="tab-pane <?= (!empty($this->params['named']) ? "fade active in" : "") ?>" id="articles">

			<h2>Related Articles</h2>

			<table class="table table-striped">
				<thead>
				    <tr>
				        <th><?= $this->Paginator->sort('title') ?></th>
				        <th><?= $this->Paginator->sort('created') ?></th>
				        <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
				    </tr>
				</thead>
				<tbody>
					<?php foreach($articles as $article): ?>
						<tr>
							<td>
								<?= $this->Html->link($article['Article']['title'], array(
									'controller' => 'articles', 
									'action' => 'edit', 
									$article['Article']['id']
								), array('target' => '_blank')) ?>
							</td>
							<td>
								<?= $this->Admin->time($article['Article']['created']) ?>
							</td>
							<td>
								<?= $this->Html->link($article['User']['username'], array(
									'controller' => 'users', 
									'action' => 'edit', 
									$article['User']['id']
								), array('target' => '_blank')) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<div class="pull-left">
				<?= $this->element('admin_pagination') ?>
			</div>
			<div class="pull-right" style="margin-top:18px">
				<?= $this->Html->link('Add Article <i class="fa fa-plus"></i>', array(
					'controller' => 'articles', 
					'action' => 'add', 
					$this->request->data['Category']['id']
					), array('class' => 'btn btn-primary', 'escape' => false, 'target' => '_blank')
				) ?>
			</div>

			<div class="clearfix"></div>
		</div>
	<?php endif ?>
	<?php if (!empty($fields)): ?>
		<div class="tab-pane" id="fields">

			<h2>Related Fields</h2>

			<table class="table table-striped">
				<thead>
				    <tr>
				        <th>Title</th>
				        <th>Type</th>
				        <th>Created</th>
				    </tr>
				</thead>
				<tbody>
					<?php foreach($fields as $field): ?>
						<tr>
							<td>
								<?= $this->Html->link($field['Field']['title'], array(
									'controller' => 'fields', 
									'action' => 'edit', 
									$field['Field']['id']
								), array('target' => '_blank')) ?>
							</td>
							<td>
								<?= $field['FieldType']['title'] ?>
							</td>
							<td>
								<?= $this->Admin->time($field['Field']['created']) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<div class="pull-right" style="margin-top:18px">
				<?= $this->Html->link('Add Field <i class="fa fa-plus"></i>', array(
					'controller' => 'fields', 
					'action' => 'add', 
					$this->request->data['Category']['id']
					), array('class' => 'btn btn-primary', 'escape' => false, 'target' => '_blank')
				) ?>
			</div>

			<div class="clearfix"></div>
		</div>
	<?php endif ?>
</div>