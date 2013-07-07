<?= $this->Html->script('jquery.fancybox.js') ?>
<?= $this->Html->css('fancybox/jquery.fancybox') ?>

<script>
$(document).ready(function() {
	$(".fancybox").fancybox();
});
</script>

<?php $this->Html->addCrumb('Media Index', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('View Media Library', null) ?>

<h1 class="pull-left">
	<?= $media['Media']['title'] ?>
</h1>

<?= $this->Html->link(
		'<i class="icon-arrow-left icon-white"></i> Library List', 
		array('action' => 'index'), 
		array(
			'class' => 'btn btn-primary pull-right', 
			'escape' => false,
			'style' => 'margin-bottom: 10px'
		)
) ?>

<div class="clearfix"></div>

<?php if (empty($files)): ?>
    <div class="well">
        No Images found
    </div>
<?php else: ?>
	<ul class="thumbnails">
		<?php foreach($files as $key => $row): ?>
			<li class="span4<?= ($key % 3 === 0 ? ' no-marg-left' : '') ?>">
				<?php
				$url = array('controller' => 'files', 'action' => 'view', $row['File']['id']);
				?>

				<?= $this->Html->link(
						$this->Html->image(
							DS . $row['File']['dir'] . $row['File']['filename'],
							array(
								'style' => 'width: 300px;height: 200px'
							)
						),
						DS . $row['File']['dir'] . $row['File']['filename'],
						array(
							'class' => 'fancybox thumbnail',
							'rel' => $media['Media']['title'],
							'title' => empty($row['File']['caption']) ? $row['File']['filename'] : $row['File']['caption'],
							'escape' => false
						)
				) ?>
			</li>
		<?php endforeach ?>
	</ul>

	<div class="clearfix"></div>

	<?= $this->element('admin_pagination') ?>
<?php endif ?>