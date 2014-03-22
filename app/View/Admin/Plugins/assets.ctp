<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Plugin Files', null) ?>

<h2><?= $plugin ?> Files</h2>

<div class="btn-toolbar pull-right">
	<?= $this->Html->link('Add File <i class="fa fa-plus"></i>', array(
			'controller' => 'themes',
			'action' => 'admin_asset_add', 
			null,
			'Plugin' . $plugin
		), array(
			'class' => 'btn btn-info',
			'escape' => false
	)) ?>
	<?= $this->Html->link(
	    '<i class="fa fa-list"></i> Back to Plugins',
	    array('action' => 'index'),
	    array('class' => 'btn btn-info', 'escape' => false)
	) ?>
</div>
<div class="clearfix"></div>

<?php if (empty($assets_list['assets'])): ?>
	<div class="well">
		There are no files
	</div>
<?php else: ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>File</th>
				<th class="hidden-xs">Type</th>
				<th>Size</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($assets_list['assets'] as $key => $row): ?>
				<tr>
					<td>
						<?= $this->Html->link($row, array(
							'controller' => 'themes',
							'action' => 'admin_asset_edit',
							$key,
							null,
							'Plugin' . $plugin
						)) ?>
                        <?= $this->Html->link(
                            '<i class="fa fa-globe" title="View File"></i>',
                            $assets_list['view_path'] . $row,
                            array('class' => 'pull-right', 'target' => '_blank', 'escape' => false));
                        ?>
					</td>
					<td class="hidden-xs">
						<?= pathinfo($assets_list['path'] . $row, PATHINFO_EXTENSION) ?>
					</td>
					<td>
						<?= $this->Number->toReadableSize( filesize($assets_list['path'] . $row ) ) ?>
					</td>
					<td>
						<div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <?= $this->Admin->edit(
                                        $key,
                                        'themes',
                                        null,
                                        'admin_asset_edit',
                                        'Plugin' . $plugin
                                    ) ?>
                                </li>
                                <li>
                                    <?= $this->Admin->delete(
                                        $key,
                                        'Plugin' . $plugin,
                                        'asset',
                                        'themes',
                                        'admin_asset_delete'
                                    ) ?>
                                </li>
                                <li>
									<?= $this->Html->link('<i class="fa fa-picture-o"></i> View',
										$assets_list['view_path'] . $row,
										array(
											'escape' => false,
											'target' => '_blank'
										)
									) ?>
                                </li>
                            </ul>
                        </div>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>
<div class="clearfix"></div>