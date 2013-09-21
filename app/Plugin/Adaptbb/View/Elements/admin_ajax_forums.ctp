<?php foreach($forums as $key => $forum): ?>
	<li class="btn" id="<?= $forum['Forum']['id'] ?>"><i class="icon icon-move"></i>

	<?php if ($forum['Forum']['id'] == $original['id']): ?>
		<span><?= $original['title'] ?></span> 
		<i class="icon icon-question-sign" data-content="<?= $original['description'] ?>" data-title="<?= $original['title'] ?>"></i>

		<span class="label label-info pull-right">
            Current Forum
        </span>
		<?php $current = 1 ?>
	<?php else: ?>
		<span><?= $forum['Forum']['title'] ?></span>
		<i class="icon icon-question-sign" data-content="<?= htmlentities($forum['Forum']['description'], ENT_QUOTES) ?>" data-title="<?= $forum['Forum']['title'] ?>"></i>
	<?php endif ?>

	</li>
<?php endforeach ?>

<?php if (empty($current)): ?>
	<li class="btn" id="0">
		<i class="icon icon-move"></i> <?= $original['title'] ?>
		<span class="label label-info pull-right">
			Current Forum
		</span>
	</li>
<?php endif ?>