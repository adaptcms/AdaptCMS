<?php

function formatUrl($data)
{
	$ext = explode(".", $data);
	$url_data = $data;
	$extension = "";

	foreach($ext as $i => $row) {
		if ($i == 1) {
			$extension = $row;
		} elseif ($i > 1) {
			$extension .= "_".$row;
		}

		if ($i > 0) {
			$url_data = str_replace(".".$row, "", $url_data);
		}
	}

	return array(
		'url_data' => $url_data,
		'extension' => $extension
	);
}
?>

<script>
$(document).ready(function(){
	$("#ThemeAdminEditForm").validate();
});
</script>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Theme</a>
	</li>
	<li>
		<a href="#assets" data-toggle="tab">Theme Assets</a>
	</li>
</ul>

<div class="right">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index', 'controller' => 'templates'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Theme']['id'], $this->request->data['Theme']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this theme?')"));
    ?>
</div>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('Theme', array('class' => 'well')) ?>
			<h2>Edit Theme</h2>
		    
		    <?php if ($this->request->data['Theme']['id'] == 1): ?>
		    	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required', 'disabled')) ?>
		    <?php else: ?>
				<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
			<?php endif ?>

			<?= $this->Form->hidden('old_title', array('value' => $this->request->data['Theme']['title'])) ?>
			<?= $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time()))) ?>
		    <?= $this->Form->hidden('id') ?>

		<?php if ($this->request->data['Theme']['id'] == 1): ?>
			<?= $this->Form->end() ?>
		<?php else: ?>
			<?= $this->Form->end('Submit') ?>
		<?php endif ?>
	</div>

	<div class="tab-pane well" id="assets">
		<div class="pull-left">
			<h2>Assets</h2>
		</div>

		<div class="pull-right">
			<?= $this->Html->link('Add New File', array(
					'controller' => 'files', 
					'action' => 'add', 
					$this->request->data['Theme']['title']
				), array(
					'class' => 'btn'
			)) ?>
		</div>
		<div class="clearfix"></div>

		<?php if (!empty($assets_list)): ?>
			<table class="table table-bordered">
				<tr>
					<th>File</th>
					<th>Type</th>
					<th>Options</th>
				</tr>
				<?php foreach($assets_list as $key => $row): ?>
					<?php if (is_array($row)): ?>
						<?php foreach($row as $fol => $data): ?>
							<?php $type = ucfirst(pathinfo($assets_list_path.$key.'/'.$data, PATHINFO_EXTENSION)) ?>
							<?php $url = formatUrl($data) ?>

							<?php if (!empty($type)): ?>
								<tr>
									<td>
										<?= $this->Html->link($key.'/'.$data, array(
												'controller' => 'files', 
												'action' => 'edit', 
												'theme-'.$this->request->data['Theme']['title'], 
												urlencode($key.'___'.$url['url_data']),
												$url['extension']
										)) ?>
				                        <?= $this->Html->link(
				                            '<i class="icon-globe" title="View File"></i>', 
				                            $webroot_path.$key.'/'.$data,
				                            array('style' => 'float: right', 'target' => '_new', 'escape' => false));
				                        ?>
									</td>
									<td>
										<?= $type ?>
									</td>
									<td>
			                            <?= $this->Html->link(
			                                '<i class="icon-pencil icon-white"></i> Edit', 
			                                array(
			                                	'controller' => 'files', 
			                                	'action' => 'edit', 
			                                	'theme-'.$this->request->data['Theme']['title'], 
			                                	urlencode($key.'___'.$url['url_data']),
			                                	$url['extension']
			                                ),
			                                array('class' => 'btn btn-primary', 'escape' => false));
			                            ?>
			                            <?= $this->Html->link(
			                                '<i class="icon-trash icon-white"></i> Delete',
			                                array(
			                                	'controller' => 'files',
			                                	'action' => 'delete', 
			                                	'theme-'.$this->request->data['Theme']['title'], 
			                                	urlencode($key.'___'.$url['url_data']),
			                                	$url['extension']
			                                ),
			                                array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file? This will be permanent.')"));
			                            ?>
									</td>
								</tr>
							<?php endif ?>
						<?php endforeach ?>
					<?php else: ?>
						<?php $type = ucfirst(pathinfo($assets_list_path.$row, PATHINFO_EXTENSION)) ?>
						<?php $url = formatUrl($row) ?>

						<?php if (!empty($type)): ?>
							<tr>
								<td>
									<?= $this->Html->link($row, array(
											'controller' => 'files', 
											'action' => 'edit', 
											'theme-'.$this->request->data['Theme']['title'], 
											urlencode($url['url_data']),
											$url['extension']
									)) ?>
			                        <?= $this->Html->link(
			                            '<i class="icon-globe" title="View File"></i>', 
			                            $webroot_path.$row,
			                            array('style' => 'float: right', 'target' => '_new', 'escape' => false));
			                        ?>
								</td>
								<td>
							        <?= $type ?>
								</td>
								<td>
			                        <?= $this->Html->link(
			                            '<i class="icon-pencil icon-white"></i> Edit', 
			                            array(
			                            	'controller' => 'files', 
			                            	'action' => 'edit',
			                            	 'theme-'.$this->request->data['Theme']['title'], 
			                            	 urlencode($url['url_data']),
			                            	 $url['extension']
			                            ),
			                            array('class' => 'btn btn-primary', 'escape' => false));
			                        ?>
			                        <?= $this->Html->link(
			                            '<i class="icon-trash icon-white"></i> Delete',
			                            array(
			                            	'controller' => 'files', 
			                            	'action' => 'delete', 
			                            	'theme-'.$this->request->data['Theme']['title'], 
			                            	urlencode($url['url_data']),
			                            	$url['extension']
			                            ),
			                            array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file? This will be permanent.')"));
			                        ?>
								</td>
							</tr>
						<?php endif ?>
					<?php endif ?>
				<?php endforeach ?>
			</table>
		<?php endif ?>
	</div>
</div>