<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array(
	'action' => 'index',
	'controller' => 'templates'
)) ?>
<?php $this->Html->addCrumb('Edit Theme', null) ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Theme</a>
	</li>
	<li>
		<a href="#assets" data-toggle="tab">Theme Files</a>
	</li>
	<div class="right hidden-xs">
	    <?= $this->Html->link(
	        '<i class="fa fa-chevron-left"></i> Return to Index',
	        array('action' => 'index', 'controller' => 'templates'),
	        array('class' => 'btn btn-info', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="fa fa-trash-o"></i> Delete',
	        array('action' => 'delete', $this->request->data['Theme']['id'], $this->request->data['Theme']['title']),
	        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this theme?')"));
	    ?>
	</div>
</ul>

<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('Theme', array('class' => 'well admin-validate')) ?>
			<h2>Edit Theme</h2>
		    
		    <?php if ($this->request->data['Theme']['id'] == 1 || !empty($config['api_id'])): ?>
		    	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required', 'disabled')) ?>
		    <?php else: ?>
				<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
			<?php endif ?>

			<?= $this->Form->hidden('old_title', array('value' => $this->request->data['Theme']['title'])) ?>
		    <?= $this->Form->hidden('id') ?>

		<?php if ($this->request->data['Theme']['id'] == 1): ?>
			<?= $this->Form->end() ?>
		<?php else: ?>
			<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn btn-primary'
			)) ?>
		<?php endif ?>
	</div>

	<div class="tab-pane" id="assets">
		<h2 class="pull-left">Files</h2>

		<?= $this->Html->link('Add New File <i class="fa fa-plus"></i>', array(
				'action' => 'admin_asset_add', 
				$this->request->data['Theme']['title']
			), array(
				'class' => 'btn btn-info pull-right',
				'escape' => false,
				'style' => 'margin-top: 10px'
		)) ?>
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
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($assets_list['assets'] as $key => $row): ?>
						<tr>
							<td>
								<?= $this->Html->link($row, array(
										'action' => 'admin_asset_edit',
										$key,
										$this->request->data['Theme']['title']
								)) ?>
		                        <?= $this->Html->link(
		                            '<i class="fa fa-globe" title="View File"></i>',
		                            $assets_list['view_path'] . $row,
		                            array('class' => 'pull-right', 'target' => '_blank', 'escape' => false));
		                        ?>
							</td>
							<td class="hidden-xs">
								<?= pathinfo($assets_list['path']  . $row, PATHINFO_EXTENSION) ?>
							</td>
							<td>
								<?= $this->Number->toReadableSize( filesize($assets_list['path'] . DS . str_replace('&', '.', str_replace('__', '/', $key)) ) ) ?>
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
                                                null,
                                                null,
                                                'admin_asset_edit',
                                                $this->request->data['Theme']['title']
                                            ) ?>
                                        </li>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $key,
                                                $this->request->data['Theme']['title'],
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
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>

	</div>
</div>