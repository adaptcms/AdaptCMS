<link rel="stylesheet" href="<?= $this->webroot ?>css/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?= $this->webroot ?>js/jquery.fancybox.js"></script>

<script>
$(document).ready(function() {
	$(".fancybox").fancybox();
});
</script>

<h1 class="pull-left">
	<?= $media['Media']['title'] ?>
</h1>

<?= $this->Html->link(
		'<i class="icon-arrow-left"></i> Library List', 
		array('action' => 'index'), 
		array('class' => 'btn pull-right', 'escape' => false)
) ?>

<div class="clearfix"></div>
<p>&nbsp;</p>

<?php foreach($this->request->data as $row): ?>
	<div class="span3">
		<?php
		$url = array('controller' => 'files', 'action' => 'view', $row['File']['id']);
		?>

		<?= $this->Html->link(
				$this->Html->image(
					$this->webroot.$row['File']['dir'].'thumb/'.$row['File']['filename'],
					array(
						'style' => 'border: 1px solid #000'
					)
				),
				$this->webroot.$row['File']['dir'].$row['File']['filename'],
				array(
					'class' => 'fancybox',
					'rel' => $media['Media']['title'],
					'title' => $row['File']['caption'],
					'escape' => false
				)
		) ?>
	</div>
<?php endforeach ?>

<div class="clearfix"></div>

<?= $this->element('admin_pagination') ?>