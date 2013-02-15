<?php if (empty($this->request->data) && empty($sql['error'])): ?>
	<?= $this->Form->create('Uninstall', array('class' => 'well')) ?>
		<?php if (!empty($uninstall_text)): ?>
			<h2 class="no-bg">From Manufacturer</h2>

			<?= $uninstall_text ?>
		<?php endif ?>

		<h1 class="no-bg">Notice</h1>

		<p>
			Please note that Insane Visions currently reviews all plugins, but does not gurantee these plugins to be fully working and any damage caused is not our responsibility. We advise all users to review information on the official page of the Plugin and to ensure the best and safest chance of getting a plugin, to use the official website.
		</p>

		<?= $this->Form->hidden('uninstall') ?>
	<?= $this->Form->end('Un-Install Plugin') ?>
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
					The Plugin has been removed successfully! <?= $this->Html->link('Click here', array(
						'controller' => 'tools',
						'action' => 'plugins',
						'admin' => true
					)) ?> to return to the Plugins Page.
				</p>
			<?php endif ?>
		<?php endif ?>
	</div>
<?php endif ?>