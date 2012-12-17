<?= $this->Html->script('jquery.fancybox.js') ?>
<?= $this->Html->css('fancybox/jquery.fancybox') ?>

<script>
$(document).ready(function() {
	$(".fancybox").fancybox();
});
</script>

<h1>Media Libraries</h1>

<?php foreach($this->request->data as $row): ?>
	<div class="span3">
		<?php
		$url = array('action' => 'view', $row['Media']['slug']);
		?>

		<?php if (!empty($row['File'][0]['id'])): ?>
			<?= $this->Html->link(
					$this->Html->image(
						'/'.$row['File'][0]['dir'].'thumb/'.$row['File'][0]['filename'],
						array(
							'style' => 'border: 1px solid #000'
						)
					),
					$url,
					array(
						'escape' => false
					)
			) ?>
			<br />

			<?= $this->Html->link($row['Media']['title'], $url, array('class' => 'center')) ?>
		<?php else: ?>
			<?= $this->Html->link($row['Media']['title'], $url, array('class' => 'center')) ?>
		<?php endif ?>
	</div>
<?php endforeach ?>

<div class="clearfix"></div>

<?= $this->element('admin_pagination') ?>