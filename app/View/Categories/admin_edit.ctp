<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Categories', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Category', null) ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li<?= (!empty($this->params['named']) ? "" : " class='active'") ?>>
		<a href="#main" data-toggle="tab">Edit Category</a>
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
	<div class="right hidden-phone">
	    <?= $this->Html->link(
	        '<i class="icon-chevron-left"></i> Return to Index',
	        array('action' => 'index'),
	        array('class' => 'btn', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="icon-trash icon-white"></i> Delete',
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

			<?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
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
				<?= $this->Html->link('Add Article <i class="icon-plus"></i>', array(
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
								<?= $field['Field']['field_type'] ?>
							</td>
							<td>
								<?= $this->Admin->time($field['Field']['created']) ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<div class="pull-right" style="margin-top:18px">
				<?= $this->Html->link('Add Field <i class="icon-plus"></i>', array(
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