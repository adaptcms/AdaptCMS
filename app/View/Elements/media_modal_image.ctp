<div id="file-<?= $image['id'] ?>" class="file_info span4">
	<?php if (file_exists($image_path.$image['dir'].$image['filename'])): ?>
		<?= $this->Html->image($this->params->webroot.$image['dir'].'thumb/'.$image['filename'], array('style' => 'cursor: pointer')) ?>
	<?php else: ?>
		<?= $this->Html->image('http://placehold.it/150x150', array('style' => 'cursor: pointer')) ?>
	<?php endif ?>

	<h4 style="margin-top: 10px">
		<?= $image['filename'] ?>
		<?= $this->Form->input('File.' . $key, array(
				'type' => 'checkbox', 
				'value' => $image['id'], 
				'div' => false,
				'label' => false,
				'class' => 'file',
				'checked' => (!empty($check) ? 'checked' : '')
		)) ?>
	</h4>

	<span style="font-style: italic">
		Uploaded <?= $this->Time->format('F d, Y', $image['created']) ?>
	</span>
</div>