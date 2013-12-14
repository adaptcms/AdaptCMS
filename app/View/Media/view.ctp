<?= $this->Html->script('../css/fancybox/jquery.fancybox.js') ?>
<?= $this->Html->css('fancybox/jquery.fancybox') ?>

<?= $this->Html->script('../css/fancybox/helpers/jquery.fancybox-thumbs.js') ?>
<?= $this->Html->css('fancybox/helpers/jquery.fancybox-thumbs') ?>

<script>
$(document).ready(function() {
	$(".fancybox").fancybox({
		prevEffect: 'fade',
		nextEffect: 'fade',
		helpers: {
			title: {
				type: 'outside'
			},
			thumbs: {
				width: 50,
				height: 50
			}
		}
	});
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
	<ul class="thumbnails no-pad-left">
		<?php foreach($files as $key => $row): ?>
			<li class="col-lg-4 list-unstyled<?= ($key % 3 === 0 ? ' no-marg-left' : '') ?>">
				<?php
				$url = array('controller' => 'files', 'action' => 'view', $row['File']['id']);
				?>

				<?= $this->Html->link(
						$this->Html->image(
							DS . $row['File']['dir'] . 'thumb' . DS . $row['File']['filename'],
							array(
								'style' => 'width: 390px;height: 230px'
							)
						),
						DS . $row['File']['dir'] . $row['File']['filename'],
						array(
							'class' => 'fancybox thumbnail',
							'rel' => 'fancybox',
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