<a name="comment_<?= $data['Comment']['id'] ?>"></a>

<div class="span5 well comment level_<?= $level ?>" id="comment-<?= $data['Comment']['id'] ?>">
	<div class="header">
		<h5>
			<?php if (!empty($data['User']['username'])): ?>
				<?= $this->Html->link($data['User']['username'], array(
					'controller' => 'users',
					'action' => 'profile',
					$data['User']['username']
				)) ?>
			<?php else: ?>
				Guest
			<?php endif ?>
			 @ 
			<?= $this->Admin->time($data['Comment']['created'], 'words') ?>
		</h5>
	</div>

	<div class="body">
		<?= $data['Comment']['comment_text'] ?>
	</div>

	<?php if ($level != 3): ?>
		<div class="footer">
			<?= $this->Html->link('reply', '#reply', array('class' => 'pull-right')) ?>
		</div>
	<?php endif ?>
</div>

<div class="clearfix"></div>