<?php $this->Html->addCrumb('Media Index', null) ?>

<h1>Media Libraries</h1>

<?php if (empty($media)): ?>
    <div class="well">
        No Libraries found
    </div>
<?php else: ?>
	<ul class="thumbnails no-pad-left">
		<?php foreach($media as $key => $row): ?>
			<?php
			$url = array('action' => 'view', $row['Media']['slug']);
			?>

			<li class="col-lg-3 list-unstyled panel panel-inverse">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?= $this->Html->link($row['Media']['title'], $url) ?>
						<small>
							<?= $row['File']['count'] ?> Images
						</small>
					</h3>
				</div>

				<?php if (!empty($row['File']['id'])): ?>
					<?= $this->Html->link(
							$this->Html->image(
								'/'.$row['File']['dir'].'thumb/' . $row['File']['filename'],
								array(
									'style' => 'width: 267px;height: 200px'
								)
							),
							$url,
							array(
								'escape' => false,
								'class' => ''
							)
					) ?>
				<?php endif ?>
				<div class="caption">
					<em>
						Posted @
						<?= $this->Admin->time($row['Media']['created'], 'words') ?>
					</em>
				</div>
			</li>
		<?php endforeach ?>
	</ul>

	<div class="clearfix"></div>

	<?= $this->element('admin_pagination') ?>
<?php endif ?>