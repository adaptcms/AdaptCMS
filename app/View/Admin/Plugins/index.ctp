<script type="text/javascript">
$(document).ready(function() {
    $(".plugin-info").popover({
        trigger: 'hover',
        placement: 'left'
    });
});
</script>

<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', null) ?>

<h1 class="pull-left">Plugins</h1>

<div class="btn-toolbar pull-right admin-edit-options">
	<?= $this->Html->link('Create a Plugin <i class="fa fa-plus"></i>', array('controller' => 'tools', 'action' => 'create_plugin'), array(
		'class' => 'btn btn-success',
		'escape' => false
	)) ?>
	<?= $this->Html->link('Get More Plugins <i class="fa fa-search"></i>', $this->Api->url() . 'plugins', array(
	    'class' => 'btn btn-info',
	    'target' => '_blank',
	    'escape' => false
	)) ?>
</div>
<div class="clearfix"></div>

<?php if (empty($plugins)): ?>
    <div class="well">
        No Plugins - <?= $this->Html->link('Click here', $this->Api->url() . 'plugins', array(
            'target' => '_blank',
        )) ?> to look for Plugins on the official website.
    </div>
<?php else: ?>
	<div class="table-responsive">
	    <table class="table table-striped">
	        <thead>
	            <tr>
	                <th>Plugin Name</th>
	                <th>Status</th>
	                <th class="hidden-xs">Current Version</th>
	                <th class="hidden-xs">Details</th>
	                <th></th>
	            </tr>
	        </thead>

	        <tbody>
	            <?php foreach ($plugins as $key => $plugin): ?>
	                <?php if (!empty($plugin['current_version'])): ?>
	                    <?php if (!empty($plugin['data'])): ?>
	                        <?php if ($plugin['data']['current_version'] == $plugin['current_version']): ?>
	                            <tr class="plugin-info" data-title="<?= $plugin['data']['title'] ?>" data-content="Plugin is up to date">
	                        <?php else: ?>
	                            <tr class="plugin-info" data-title="<?= $plugin['data']['title'] ?>" data-content="Plugin is out of date, newest version is <?= $plugin['data']['current_version'] ?>">
	                        <?php endif ?>
	                    <?php else: ?>
	                        <tr>
	                    <?php endif ?>
	                <?php else: ?>
	                    <tr>
	                <?php endif ?>
	                    <td style="width:30%">
	                        <?php if (!empty($plugin['data']['short_description'])): ?>
		                        <?php if ($plugin['status'] == 1): ?>
	                                <strong><?= $plugin['title'] ?></strong>
		                        <?php else: ?>
	                                <span class="disabled"><?= $plugin['title'] ?></span>
		                        <?php endif ?>

	                            <div class="span12 no-marg-left">
	                                <?= $plugin['data']['short_description'] ?>
	                            </div>
	                        <?php else: ?>
		                        <?php if ($plugin['status'] == 1): ?>
			                        <strong><?= $plugin['title'] ?></strong>
		                        <?php else: ?>
			                        <span class="disabled"><?= $plugin['title'] ?></span>
		                        <?php endif ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <?php if ($plugin['status'] == 1): ?>
	                            Active
	                        <?php else: ?>
	                            In-Active
	                        <?php endif ?>
	                    </td>
	                    <?php if (!empty($plugin['current_version'])): ?>
	                        <?php if (!empty($plugin['data'])): ?>
	                            <?php if ($plugin['data']['current_version'] == $plugin['current_version']): ?>
	                                <td class="hidden-xs">
	                                    <?= $plugin['current_version'] ?>
	                                    <i class="fa fa-check info"></i>
	                                </td>
	                            <?php else: ?>
	                                <td class="hidden-xs">
	                                    <?= $plugin['current_version'] ?>
	                                    <i class="fa fa-ban"></i>

	                                    <?= $this->Html->link('Download Latest Version <i class="fa fa-download"></i>',
	                                        $this->Api->url() . 'plugin/' . $plugin['data']['slug'],
	                                        array('target' => '_blank', 'class' => 'btn btn-primary pull-right', 'escape' => false)
	                                    ) ?>
	                                </td>
	                            <?php endif ?>
	                        <?php else: ?>
	                            <td class="hidden-xs">
	                                <?= $plugin['current_version'] ?>
	                            </td>
	                        <?php endif ?>
	                    <?php else: ?>
	                        <td class="hidden-xs"></td>
	                    <?php endif ?>
	                    <td class="hidden-xs">
	                        <?php if (!empty($plugin['data'])): ?>
	                            <?php if (!empty($plugin['data']['author_url'])): ?>
	                                <?= $this->Html->link($plugin['data']['author_name'], $plugin['data']['author_url'], array('target' => '_blank')) ?> |
	                            <?php endif ?>

	                            <?= $this->Html->link('Plugin Page',
	                                $this->Api->url() . 'plugin/' . $plugin['data']['slug'],
	                                array('target' => '_blank')
	                            ) ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu pull-right">
	                                <?php if ($plugin['status'] == 1): ?>
	                                    <?php if (!empty($plugin['upgrade'])): ?>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="fa fa-upload"></i> Upgrade',
	                                                array(
	                                                    'admin' => false,
	                                                    'controller' => 'install',
	                                                    'action' => 'upgrade_plugin',
	                                                    $key
	                                                ),
	                                                array(
	                                                    'escape' => false
	                                                ));
	                                            ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($plugin['config'] == 1): ?>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="fa fa-cog"></i> Settings',
	                                                array(
	                                                    'action' => 'settings',
	                                                    $key
	                                                ),
	                                                array(
	                                                    'escape' => false
	                                                ));
	                                            ?>
	                                        </li>
	                                    <?php endif ?>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="fa fa-picture-o"></i> Files',
	                                                array(
	                                                    'action' => 'assets',
	                                                    $key
	                                                ),
	                                                array(
	                                                    'escape' => false
	                                                ));
	                                            ?>
	                                        </li>
	                                        <li>
	                                            <?= $this->Html->link(
	                                                '<i class="fa fa-group"></i> Permissions',
	                                                array(
	                                                    'action' => 'permissions',
	                                                    $key
	                                                ),
	                                                array(
	                                                    'escape' => false
	                                                ));
	                                            ?>
	                                        </li>
	                                    <li>
	                                        <?= $this->Html->link(
	                                            '<i class="fa fa-trash-o"></i> Un-Install',
	                                            array(
	                                                'admin' => false,
	                                                'controller' => 'install',
	                                                'action' => 'uninstall_plugin',
	                                                $key
	                                            ),
	                                            array(
	                                                'escape' => false,
	                                                'onclick' => "return confirm('Are you sure you want to uninstall this plugin? This is permanent.')"
	                                            ));
	                                        ?>
	                                    </li>
	                                <?php else: ?>
	                                    <li>
	                                        <?= $this->Html->link(
	                                            '<i class="fa fa-plus"></i> Install',
	                                            array(
	                                                'admin' => false,
	                                                'controller' => 'install',
	                                                'action' => 'install_plugin',
	                                                $key
	                                            ),
	                                            array(
	                                                'escape' => false
	                                            ));
	                                        ?>
	                                    </li>
	                                <?php endif ?>
	                            </ul>
	                        </div>
	                    </td>
	                </tr>
	            <?php endforeach ?>
	        </tbody>
	    </table>
	</div>
<?php endif ?>