<link rel="stylesheet" href="<?= $this->webroot ?>css/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?= $this->webroot ?>js/jquery.fancybox.js"></script>

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
						$this->webroot.$row['File'][0]['dir'].'thumb/'.$row['File'][0]['filename'],
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