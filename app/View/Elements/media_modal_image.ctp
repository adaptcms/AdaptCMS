<?php if ($key != 0 && $key % 3 === 0): ?>
	<!-- <div class="clearfix"></div> -->
<?php endif ?>

<li id="file-<?= $image['id'] ?>" class="file_info span4 center">
	<?php if (file_exists($image_path.$image['dir'].$image['filename'])): ?>
		<?= $this->Html->image('/'.$image['dir'].$image['filename'], array(
			'style' => 'cursor: pointer;max-width: 265px;max-height: 185px;display: inline', 
			'class' => 'thumbnail'
		)) ?>
	<?php else: ?>
		<?= $this->Html->image('http://placehold.it/300x200', array('style' => 'cursor: pointer', 'class' => 'thumbnail')) ?>
	<?php endif ?>

	<h4 style="margin-top: 10px">
		<?= wordwrap($image['filename'], 35, "<br />", true) ?>
		<?= $this->Form->input((empty($modal) ? 'File.' . $key : 'Files.' . $key), array(
				'type' => 'checkbox', 
				'value' => $image['id'], 
				'div' => false,
				'label' => false,
				'class' => 'file',
				'checked' => (!empty($check) ? 'checked' : '')
		)) ?>
	</h4>

	<em>
		Uploaded <?= $this->Time->format('F d, Y', $image['created']) ?>
	</em>
</li>