<?php foreach($data as $row): ?>
	<li>
		<?php if (!empty($row['Link']['file_id'])): ?>
			<?php
				$title = $this->Html->image($this->webroot.$row['File']['dir'].$row['File']['filename']);
			?>
		<?php elseif (!empty($row['Link']['image_url'])): ?>
			<?php
				$title = $this->Html->image($row['Link']['image_url']);
			?>
		<?php else: ?>
			<?php $title = $row['Link']['link_title'] ?>
		<?php endif ?>

		<?= $this->Html->link($title, $row['Link']['url'], array(
				'target' => $row['Link']['link_target'],
				'escape' => false,
				'class' => 'track',
				'id' => $row['Link']['id']
		)) ?>
	</li>
<?php endforeach ?>