<?php if (!empty($data['data'])): ?>
	<?php foreach($data['data'] as $row): ?>
		<?= $row ?><?= (end($data['data']) != $row ? ', ' : '') ?>
	<?php endforeach ?>
<?php endif ?>