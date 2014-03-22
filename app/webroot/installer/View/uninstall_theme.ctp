<?php if (empty($this->request->data) && empty($sql['error'])): ?>
	<?= $this->Form->create('Uninstall', array('class' => 'well')) ?>
		<h1>Un-Installing <?php echo $theme ?> Theme</h1>

		<?php if (!empty($uninstall_text)): ?>
			<h2 class="no-bg">From Manufacturer</h2>

			<?= $uninstall_text ?>
		<?php endif ?>

		<h1 class="no-bg">Notice</h1>

		<p>
			Please note that Insane Visions currently reviews all themes, but does not gurantee these themes to be fully working and any damage caused is not our responsibility. We advise all users to review information on the official page of the Theme and to ensure the best and safest chance of getting a theme, to use the official website.
		</p>

		<?= $this->Form->hidden('uninstall') ?>
	<?= $this->Form->end('Un-Install Theme') ?>
<?php else: ?>
	<div class="well">
		<?php if (!empty($sql)): ?>

			<?php foreach($sql['sql'] as $file => $count): ?>
				<h2>
					<?= $file ?>
				</h2>

				<?php if ($count['total'] == $count['success']): ?>
					<span class="notice success">
						SQL Data Removed Successfully
					</span>
				<?php else: ?>
					<span class="notice success">
						Unable to removed all SQL Data. <?= $count['success'] ?> of <?= $count['total'] ?> removed. Go back and try again
					</span>
				<?php endif ?>
			<?php endforeach ?>

			<?php if (empty($sql['sql']['error'])): ?>
				<p>
					The Theme has been removed successfully! <?= $this->Html->link('Click here', array(
						'controller' => 'templates',
						'action' => 'index',
						'admin' => true
					)) ?> to return to the Appearance Page.
				</p>
			<?php endif ?>
		<?php endif ?>
	</div>
<?php endif ?>