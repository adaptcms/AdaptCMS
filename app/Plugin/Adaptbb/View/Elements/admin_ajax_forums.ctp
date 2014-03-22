<?php foreach($forums as $key => $forum): ?>
	<li class="btn btn-success" id="<?= $forum['Forum']['id'] ?>"><i class="fa fa-arrows"></i>

	<?php if ($forum['Forum']['id'] == $original['id']): ?>
		<span><?= $original['title'] ?></span>

		<?php if (!empty($original['description'])): ?>
			<i class="fa fa-question-circle field-desc" data-content="<?= $original['description'] ?>" data-title="<?= $original['title'] ?>"></i>
		<?php endif ?>

		<span class="label label-info pull-right">
            Current Forum
        </span>
		<?php $current = 1 ?>
	<?php else: ?>
		<span><?= $forum['Forum']['title'] ?></span>

		<?php if (!empty($forum['Forum']['description'])): ?>
			<i class="fa fa-question-circle field-desc" data-content="<?= htmlentities($forum['Forum']['description'], ENT_QUOTES) ?>" data-title="<?= $forum['Forum']['title'] ?>"></i>
		<?php endif ?>
	<?php endif ?>

	</li>
<?php endforeach ?>

<?php if (empty($current)): ?>
	<li class="btn btn-success" id="0">
		<i class="fa fa-arrows"></i> <?= $original['title'] ?>
		<span class="label label-info pull-right">
			Current Forum
		</span>
	</li>
<?php endif ?>