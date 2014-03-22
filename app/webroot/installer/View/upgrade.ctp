<?php if (empty($this->request->data) && empty($sql['error'])): ?>
	<?= $this->Form->create('Upgrade', array('class' => 'well')) ?>
		<?php if (!empty($upgrade_text)): ?>
			<h2 class="no-bg">Upgrade Notes</h2>

			<?= $upgrade_text ?>
		<?php endif ?>

		<?= $this->Form->hidden('upgrade') ?>
	<?= $this->Form->end('Upgrade from ' . Inflector::humanize($version)) ?>
<?php else: ?>
	<div class="well">
		<?php if (!empty($sql_results)): ?>

			<?php foreach($sql_results as $file): ?>
				<h2 class="no-bg">
					<?= $file['file'] ?>
				</h2>

				<?php if (empty($file['error'])): ?>
					<span class="notice success">
						SQL Data Inserted Successfully
					</span>
				<?php else: ?>
					<?php $error = 1 ?>
					<span class="notice success">
						Unable to insert all SQL Data. <?= $file['data'][0] ?> of <?= $file['data'][1] ?> inserted. Go back and try again
					</span>
				<?php endif ?>
			<?php endforeach ?>

			<?php if (empty($error)): ?>
				<p>
					AdaptCMS has been upgraded successfully! <?= $this->Html->link('Click here', '/') ?> to return to your home page.
				</p>
			<?php endif ?>
		<?php endif ?>
	</div>
<?php endif ?>