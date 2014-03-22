<?php foreach($fields as $key => $field): ?>
	<li class="btn btn-success" id="<?= $field['Field']['id'] ?>"><i class="fa fa-arrows"></i>

	<?php if ($field['Field']['id'] == $original['id']): ?>
		<span><?= $original['title'] ?></span>

		<?php if (!empty($original['description'])): ?>
			<i class="fa fa-question-circle field-desc" data-content="<?= $original['description'] ?>" data-title="<?= $original['label'] ?>"></i>
		<?php endif ?>

		<span class="label label-info pull-right">
			Current Field
		</span>
		<?php $current = 1 ?>
	<?php else: ?>
		<span><?= $field['Field']['label'] ?></span>

		<?php if (!empty($field['Field']['description'])): ?>
			<i class="fa fa-question-circle field-desc" data-content="<?= htmlentities($field['Field']['description'], ENT_QUOTES) ?>" data-title="<?= $field['Field']['label'] ?>"></i>
		<?php endif ?>
	<?php endif ?>

	</li>
<?php endforeach ?>

<?php if (empty($current)): ?>
	<li class="btn btn-success" id="0">
		<i class="fa fa-arrows"></i> <?= $original['title'] ?>
		<span class="label label-info pull-right">
			Current Field
		</span>
	</li>
<?php endif ?>