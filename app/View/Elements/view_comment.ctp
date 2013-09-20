<a name="comment_<?= $data['Comment']['id'] ?>"></a>

<div class="span5 well comment level_<?= $level ?>" id="comment-<?= $data['Comment']['id'] ?>">
	<div class="header pull-left">
		<h5>
			<span class="pull-left">
				<?php if (!empty($data['User']['username'])): ?>
					<?= $this->Html->link($data['User']['username'], array(
						'controller' => 'users',
						'action' => 'profile',
						$data['User']['username']
					)) ?>
				<?php elseif(!empty($data['Comment']['author_name']) && !empty($data['Comment']['author_website'])): ?>
					<?= $this->Html->link($data['Comment']['author_name'], $data['Comment']['author_website'], array('target' => '_blank')) ?>
				<?php elseif(!empty($data['Comment']['author_name']) && empty($data['Comment']['author_website'])): ?>
					<?= $data['Comment']['author_name'] ?>
				<?php else: ?>
					Guest
				<?php endif ?>
				 @
				<?= $this->Admin->time($data['Comment']['created'], 'words') ?>
			</span>
		</h5>
	</div>
	<div class="btn-group pull-right">
		<?php if (!empty($permissions['related']['comments']['admin_edit']) && $this->Admin->hasPermission($permissions['related']['comments']['admin_edit'], $data['User']['id'])): ?>
			<?= $this->Html->link('edit <i class="icon-pencil"></i>', array(
				'admin' => true,
				'controller' => 'comments',
				'action' => 'edit',
				$data['Comment']['id']
			), array('escape' => false)) ?>
		<?php endif ?>
		<?php if (!empty($permissions['related']['comments']['admin_delete']) && $this->Admin->hasPermission($permissions['related']['comments']['admin_delete'], $data['User']['id'])): ?>
			<?= $this->Html->link('delete <i class="icon-trash"></i>', array(
				'admin' => true,
				'controller' => 'comments',
				'action' => 'delete',
				$data['Comment']['id']
			), array('class' => 'btn-confirm', 'escape' => false)) ?>
		<?php endif ?>
	</div>
	<div class="clearfix"></div>

	<div class="body">
		<?= $data['Comment']['comment_text'] ?>

		<?php if (!empty($data['Data'])): ?>
			<?php foreach($data['Data'] as $field => $value): ?>
				<dt>
					<?= Inflector::humanize($field) ?>
				</dt>
				<dd>
					<?= $value ?>
				</dd>
			<?php endforeach ?>
		<?php endif ?>
	</div>

	<?php if ($level != 3): ?>
		<div class="footer">
			<?= $this->Html->link('reply <i class="icon-reply"></i>', '#reply', array('class' => 'pull-right', 'escape' => false)) ?>
		</div>
		<div class="clearfix"></div>
	<?php endif ?>
</div>

<div class="clearfix"></div>