<script>
    $(document).ready(function() {
        $(".upgrade").live('click', function() {
            window.location.href = $("#webroot").text() + "<?= $theme ?>/" + $(this).attr('id');
        });
    });
</script>

<?php if (empty($this->request->data) && empty($sql['error'])): ?>
    <?= $this->Form->create('Upgrade', array('class' => 'well')) ?>
    <h1 class="no-bg">Notice</h1>

    <p>
        Please note that Insane Visions currently reviews all themes, but does not gurantee these themes to be fully working and any damage caused is not our responsibility.
        We advise all users to review information on the official page of the Theme and to ensure the best and safest chance of getting a theme, to use the
        <?= $this->Html->link('official website', Configure::read('Component.Api.api_url'), array('target' => '_blank')) ?>.
    </p>

    <?php if (!empty($version)): ?>
        <?php if (!empty($upgrade_text)): ?>
            <h2 class="no-bg">Upgrade Notes From Manufacturer</h2>

            <?= $upgrade_text ?>
        <?php endif ?>

        <?= $this->Form->submit('Confirm Upgrade to ' . $version, array('class' => 'btn')) ?>
    <?php else: ?>
        <?php if (empty($versions)): ?>
            No Upgrade Available.
            <?= $this->Html->link('Click here', array(
                'controller' => 'themes',
                'action' => 'index',
                'admin' => true
            )) ?> to return to the Themes Page.
        <?php else: ?>
            <div class="btn-group">
                <?php if (!empty($versions)): ?>
                    <?php foreach($versions as $key => $version): ?>
                        <button id="<?= $key ?>" class="btn upgrade" type="button">Upgrade from <?= ucfirst($version) ?></button>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
        <?php endif ?>
    <?php endif ?>

    <?= $this->Form->hidden('upgrade') ?>
    <?= $this->Form->end() ?>
<?php else: ?>
    <div class="well">
        <?php if (!empty($sql)): ?>

            <?php foreach($sql['sql'] as $file => $count): ?>
                <h2>
                    <?= $file ?>
                </h2>

                <?php if ($count['total'] == $count['success']): ?>
                    <span class="notice success">
						SQL Data Inserted Successfully
					</span>
                <?php else: ?>
                    <span class="notice success">
						Unable to insert all SQL Data. <?= $count['success'] ?> of <?= $count['total'] ?> inserted. Go back and try again
					</span>
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if (empty($sql['sql']['error']) && !empty($error)): ?>
            Please manually remove the following file:

            <p>
                <?= $error ?>
            </p>

            Then you can <?= $this->Html->link('Click here', array(
                'controller' => 'templates',
                'action' => 'index',
                'admin' => true
            )) ?> to return to the Appearance Page.
        <?php elseif (empty($sql['sql']['error']) && empty($error)): ?>
            <p>
                The Theme has been upgraded successfully! <?= $this->Html->link('Click here', array(
                    'controller' => 'templates',
                    'action' => 'index',
                    'admin' => true
                )) ?> to return to the Appearance Page.
            </p>
        <?php endif ?>
    </div>
<?php endif ?>