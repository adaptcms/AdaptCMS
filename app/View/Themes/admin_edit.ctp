<script>
 $(document).ready(function(){
    $("#ThemeAdminEditForm").validate();
 });
 </script>

<h1>Edit Theme</h1>
<?php
    echo $this->Form->create('Theme', array('class' => 'well'));
    
    if ($this->request->data['Theme']['id'] == 1) {
    	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required', 'disabled'));
    } else {
		echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	}

	echo $this->Form->hidden('old_title', array('value' => $this->request->data['Theme']['title']));
	echo $this->Form->hidden('modified', array('type' => 'hidden'));
    echo $this->Form->input('id', array('type' => 'hidden'));
    echo $this->Form->end('Submit');
?>

<div class="pull-left">
	<h1>Assets</h1>
</div>

<div class="pull-right">
	<?= $this->Html->link('Add New File', array(
			'controller' => 'files', 
			'action' => 'add', 
			$this->request->data['Theme']['id']
		), array(
			'class' => 'btn'
	)) ?>
</div>
<div class="clear"></div>

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
					<tr>
						<td>
							<?= $this->Html->link($key.'/'.$data, array(
									'controller' => 'themes', 
									'action' => 'edit_file', 
									$this->request->data['Theme']['id'], urlencode($key.'/'.$data)
							)) ?>
	                        <?= $this->Html->link(
	                            '<i class="icon-pencil icon-globe"></i>', 
	                            $webroot_path.$key.'/'.$data,
	                            array('style' => 'float: right', 'target' => '_new', 'escape' => false));
	                        ?>
						</td>
						<td>
							<?= ucfirst(pathinfo($assets_list_path.$key.'/'.$data, PATHINFO_EXTENSION)) ?>
						</td>
						<td>
                            <?= $this->Html->link(
                                '<i class="icon-pencil icon-white"></i> Edit', 
                                array(
                                	'controller' => 'themes', 
                                	'action' => 'edit_file', 
                                	$this->request->data['Theme']['id'], 
                                	urlencode($key.'/'.$data
                                )),
                                array('class' => 'btn btn-primary', 'escape' => false));
                            ?>
                            <?= $this->Html->link(
                                '<i class="icon-trash icon-white"></i> Delete',
                                array(
                                	'controller' => 'themes',
                                	'action' => 'delete_file', 
                                	$this->request->data['Theme']['id'], 
                                	urlencode($key.'/'.$data
                                )),
                                array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file? This will be permanent.')"));
                            ?>
						</td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td>
						<?= $this->Html->link($row, array(
								'controller' => 'themes', 
								'action' => 'edit_file', 
								$this->request->data['Theme']['id'], urlencode($row)
						)) ?>
                        <?= $this->Html->link(
                            '<i class="icon-pencil icon-globe"></i>', 
                            $webroot_path.$row,
                            array('style' => 'float: right', 'target' => '_new', 'escape' => false));
                        ?>
					</td>
					<td>
				        <?= ucfirst(pathinfo($assets_list_path.$row, PATHINFO_EXTENSION)) ?>
					</td>
					<td>
                        <?= $this->Html->link(
                            '<i class="icon-pencil icon-white"></i> Edit', 
                            array(
                            	'controller' => 'themes', 
                            	'action' => 'edit_file',
                            	 $this->request->data['Theme']['id'], 
                            	 urlencode($row)
                            ),
                            array('class' => 'btn btn-primary', 'escape' => false));
                        ?>
                        <?= $this->Html->link(
                            '<i class="icon-trash icon-white"></i> Delete',
                            array(
                            	'controller' => 'themes', 
                            	'action' => 'delete_file', 
                            	$this->request->data['Theme']['id'], 
                            	urlencode($row)
                            ),
                            array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file? This will be permanent.')"));
                        ?>
					</td>
				</tr>
			<?php endif ?>
		<?php endforeach ?>
	</table>
<?php endif ?>