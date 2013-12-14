<?php if (!empty($data['data'])): ?>
	<?php $values = json_decode($data['data']) ?>
	<?php foreach($values as $row): ?>
		<?= $row ?><?= (end($values) != $row ? ', ' : '') ?>
	<?php endforeach ?>
<?php endif ?>