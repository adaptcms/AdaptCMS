<?php if (empty($this->request->data) && empty($sql['error'])): ?>
	<?= $this->Form->create('Install', array('class' => 'well')) ?>
		<h1>Installing <?php echo $theme ?> Theme</h1>

		<?php if (!empty($install_text)): ?>
			<h2 class="no-bg">From Manufacturer</h2>

			<?= $install_text ?>
		<?php endif ?>

		<h1 class="no-bg">Notice</h1>

		<p>
			Please note that Insane Visions currently reviews all themes, but does not gurantee these themes to be fully working and any damage caused is not our responsibility.
            We advise all users to review information on the official page of the Theme and to ensure the best and safest chance of getting a theme, to use the
            <?= $this->Html->link('official website', Configure::read('Component.Api.api_url'), array('target' => '_blank')) ?>.
		</p>

		<?= $this->Form->input('default', array(
			'label' => 'Set to Default Theme?',
			'type' => 'checkbox'
		)) ?>

		<?= $this->Form->hidden('install') ?>
	<?= $this->Form->end('Install Theme') ?>
<?php else: ?>
    <div class="well">
        <?php if (!empty($sql)): ?>

            <?php foreach($sql['sql'] as $file => $count): ?>
                <h2 class="no-bg">
                    <?= $file ?>
                </h2>

                <?php if ($count['total'] == $count['success'] && empty($sql['sql']['error'])): ?>
                    <div class="alert alert-success">
                        <strong>Success</strong>
                        SQL Data Inserted Successfully
                    </div>
                <?php else: ?>
                    <div class="alert alert-error">
                        <strong>Error</strong>
                        Unable to insert all SQL Data. <?= $count['success'] ?> of <?= $count['total'] ?> inserted. Go back and try again
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <strong>Error</strong>
                <?= $error ?>
            </div>

            <p>
                Once you have fixed the above issues, go back and try again.
            </p>
        <?php endif ?>

        <?php if (empty($sql['sql']['error'])): ?>
            <p>
                The Theme has been installed successfully! <?= $this->Html->link('Click here', array(
                    'controller' => 'templates',
                    'action' => 'index',
                    'admin' => true
                )) ?> to return to the Appearance Page.
            </p>
        <?php endif ?>
    </div>
<?php endif ?>