<?php foreach($fields as $key => $field): ?>
	<li class="btn" id="<?= $field['Field']['id'] ?>"><i class="icon icon-move"></i>

	<?php if ($field['Field']['id'] == $original['id']): ?>
		<span><?= $original['title'] ?></span>
		<i class="icon icon-question-sign field-desc" data-content="<?= $original['description'] ?>" data-title="<?= $original['label'] ?>"></i>
		<span class="label label-info pull-right">
			Current Field
		</span>
		<?php $current = 1 ?>
	<?php else: ?>
		<span><?= $field['Field']['label'] ?></span>
		<i class="icon icon-question-sign field-desc" data-content="<?= htmlentities($field['Field']['description'], ENT_QUOTES) ?>" data-title="<?= $field['Field']['label'] ?>"></i>
	<?php endif ?>

	</li>
<?php endforeach ?>

<?php if (empty($current)): ?>
	<li class="btn" id="0">
		<i class="icon icon-move"></i> <?= $original['title'] ?>
		<span class="label label-info pull-right">
			Current Field
		</span>
	</li>
<?php endif ?>