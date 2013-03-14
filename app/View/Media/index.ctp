<?= $this->Html->script('jquery.fancybox.js') ?>
<?= $this->Html->css('fancybox/jquery.fancybox') ?>

<script>
$(document).ready(function() {
	$(".fancybox").fancybox();
});
</script>

<?php $this->Html->addCrumb('Media Index', null) ?>

<h1>Media Libraries</h1>

<ul class="thumbnails">
	<?php foreach($this->request->data as $key => $row): ?>
		<li class="span4<?= ($key % 3 === 0 ? ' no-marg-left' : '') ?>">
			<div class="thumbnail">
				<?php
				$url = array('action' => 'view', $row['Media']['slug']);
				?>

				<?php if (!empty($row['File'][0]['id'])): ?>
					<?= $this->Html->link(
							$this->Html->image(
								'/'.$row['File'][0]['dir'].$row['File'][0]['filename'],
								array(
									'style' => 'width: 300px;height: 200px'
								)
							),
							$url,
							array(
								'escape' => false
							)
					) ?>
				<?php endif ?>
				<div class="caption">
					<h3>
						<?= $this->Html->link($row['Media']['title'], $url) ?>
						<small>
							<?= count($row['File']) ?> Images
						</small>
					</h3>

					<em>
						Posted @ 
						<?= $this->Admin->time($row['Media']['created'], 'words') ?>
					</em>
				</div>
			</div>
		</li>
	<?php endforeach ?>
</ul>

<div class="clearfix"></div>

<?= $this->element('admin_pagination') ?>