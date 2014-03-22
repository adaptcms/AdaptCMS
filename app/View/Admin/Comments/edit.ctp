<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Comments', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Comment', null) ?>

<?php $this->TinyMce->editor() ?>

<div class="right hidden-xs">
	<?= $this->Html->link(
		'<i class="fa fa-chevron-left"></i> Return to Index',
		array('action' => 'index'),
		array('class' => 'btn btn-info', 'escape' => false
		)) ?>
	<?= $this->Html->link(
		'<i class="fa fa-trash-o"></i> Delete',
		array('action' => 'delete', $this->request->data['Comment']['id'], ''),
		array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this comment?')"));
	?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('Comment', array('class' => 'well admin-validate')) ?>
<h2>Edit Comment</h2>

	<?= $this->Form->input('comment_text', array(
		'class' => 'wysiwyg required',
		'style' => 'width:45%;height:120px'
	)) ?>

	<?php if (!empty($fields)): ?>
		<?php foreach($fields as $key => $field): ?>
			<?= $this->Element('FieldTypes/' . $field['FieldType']['slug'], array(
				'model' => 'ModuleValue',
				'key' => $key,
				'field' => $field,
				'icon' => !empty($field['Field']['description']) ?
					"<i class='fa fa-question field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;" : ''
			)) ?>
			<?= $this->Form->hidden('ModuleValue.' . $key . '.field_id', array('value' => $field['Field']['id'])) ?>
			<?= $this->Form->hidden('ModuleValue.' . $key . '.module_id', array('value' => $this->request->data['Comment']['id'])) ?>
			<?= $this->Form->hidden('ModuleValue.' . $key . '.module_name', array('value' => 'comment')) ?>

			<?php if (!empty($field['ModuleValue'][0]['id'])): ?>
				<?= $this->Form->hidden('ModuleValue.' . $key . '.id', array('value' => $field['ModuleValue'][0]['id'])) ?>
			<?php endif ?>
		<?php endforeach ?>
	<?php endif ?>

	<?= $this->Form->input('active', array(
		'type' => 'checkbox',
		'value' => 1
	)) ?>

	<dl class="dl-horizontal">
		<dt>View Comment</dt>
		<dd>
			<?=
			$this->Html->link($this->request->data['Article']['title'], array(
				'admin' => false,
				'controller' => 'articles',
				'action' => 'view',
				'id' => $this->request->data['Article']['id'],
				'slug' => $this->request->data['Article']['slug'],
				'#' => 'comment_' . $this->request->data['Comment']['id']
			)) ?>
		</dd>

		<dt>User</dt>
		<dd>
			<?php if (empty($this->request->data['User']['id'])): ?>
				Guest
			<?php else: ?>
				<?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $this->request->data['User']['id'])): ?>
					<?=
					$this->Html->link($this->request->data['User']['username'], array(
						'controller' => 'users',
						'action' => 'profile',
						$this->request->data['User']['username']
					)) ?>
				<?php else: ?>
					<?= $this->request->data['User']['username'] ?>
				<?php endif ?>
			<?php endif ?>
		</dd>

		<dt>Author Name</dt>
		<dd>
			<?= $this->request->data['Comment']['author_name'] ?>&nbsp;
		</dd>

		<dt>Author Email</dt>
		<dd>
			<?php if (empty($this->request->data['User']['id'])): ?>
				<?= $this->request->data['Comment']['author_email'] ?>&nbsp;
			<?php else: ?>
				<?= $this->Html->link(
					$this->request->data['User']['email'],
					'mailto:' . $this->request->data['User']['email']
				) ?>
			<?php endif ?>
		</dd>

		<dt>Author Website</dt>
		<dd>
			<?= $this->request->data['Comment']['author_name'] ?>&nbsp;
		</dd>

		<dt>IP Address</dt>
		<dd>
			<?= $this->request->data['Comment']['author_ip'] ?>
		</dd>
	</dl>

	<?= $this->Form->hidden('id') ?>
	<?= $this->Form->hidden('single', array('value' => 1)) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>