<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Optimize Database', null) ?>

<div class="pull-left">
	<h1>Optimize Database</h1>
</div>
<div class="pull-right">
    <?= $this->Html->link(
        '<i class="fa fa-chevron-left"></i> Return to Tools',
        array('action' => 'index'),
        array('class' => 'btn btn-info', 'escape' => false
    )) ?>
</div>
<div class="clearfix"></div>

<div class="well">
	<?php if (!empty($messages)): ?>
		<?php foreach($messages as $table => $message): ?>
			<div class="col-lg-2 no-pad-l">
				<h4>
					<?= $table ?>
				</h4>
			</div>
			<div class="clearfix"></div>

			<?php if ($message['check'] == 1): ?>
				<div class="col-lg-5 alert alert-success pull-left">
					Table `<?= $table ?>` Checked - is Okay
				</div>
			<?php else: ?>
				<div class="col-lg-5 alert alert-error">
					Table `<?= $table ?>` Checked - NOT Okay<br />
					<?= $message['check'] ?>
				</div>

				<?php if ($message['repair'] == 1): ?>
					<div class="col-lg-5 alert alert-success">
						Table `<?= $table ?>` Repaired Successfully
					</div>
				<?php else: ?>
					<div class="col-lg-5 alert alert-error">
						Table `<?= $table ?>` NOT Repaired<br />
						<?= $message['repair'] ?>
					</div>
				<?php endif ?>
			<?php endif ?>

			<?php if ($message['analyze'] == 1): ?>
				<div class="col-lg-5 alert alert-success pull-right">
					Table `<?= $table ?>` Up to Date, already Optimized
				</div>
			<?php else: ?>
				<div class="col-lg-5 alert alert-error">
					Table `<?= $table ?>` NOT Optimized<br />
					<?= $message['analyze'] ?>
				</div>

				<?php if ($message['optimize'] == 1): ?>
					<div class="col-lg-5 alert alert-success">
						Table `<?= $table ?>` Optimized Successfully
					</div>
				<?php else: ?>
					<div class="col-lg-5 alert alert-error">
						Table `<?= $table ?>` NOT Optimized<br />
						<?= $message['optimize'] ?>
					</div>
				<?php endif ?>
			<?php endif ?>

			<div class="clearfix"></div>
		<?php endforeach ?>
	<?php endif ?>
</div>