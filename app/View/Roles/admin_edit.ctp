<script>
 $(document).ready(function(){
    $("#RoleAdminForm").validate();
 });
 </script>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Role</a>
	</li>
	<li>
		<a href="#permission" data-toggle="tab">Add Permission</a>
	</li>
	<div class="right">
	    <?= $this->Html->link(
	        '<i class="icon-chevron-left"></i> Return to Index',
	        array('action' => 'index'),
	        array('class' => 'btn', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="icon-trash icon-white"></i> Delete',
	        array('action' => 'delete', $this->request->data['Role']['id'], $this->request->data['Role']['title']),
	        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this role?')"));
	    ?>
	</div>
</ul>

<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('Role', array('class' => 'well', 'id' => 'RoleAdminForm')) ?>
			<h2>Edit Role</h2>

			<?= $this->Form->input('title', array('class' => 'required')) ?>
			<?= $this->Form->input('defaults', array('options' => array(
					'default-member' => 'Default Member', 
					'default-guest' => 'Default Guest'
				),
				'label' => 'Default Settings',
				'empty' => '- choose -'
			))
			?>

			<?php foreach($this->request->data['Permission'] as $key => $permission): ?>
				<div class="span10 no-marg-left">
					<h4>
						<?= Inflector::humanize($key) ?>
					</h4>
					<table class="table">
						<thead>
							<tr>
								<td>
								</td>
								<td>Access</td>
								<td></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach($permission as $row): ?>
								<tr>
									<td>
										<?= Inflector::humanize($row['action']) ?>
									</td>
									<td>
										<?= $this->Form->hidden('Permission.' . $row['id'] . '.id', array('value' => $row['id'])) ?>
										<?= $this->Form->input('Permission.' . $row['id'] . '.status', array(
											'type' => 'checkbox', 
											'label' => false,
											'default' => $row['status'],
											'value' => 1
										)) ?>
									</td>
									<td>
										<?php if ($row['own'] != 2): ?>
											<?= $this->Form->input('Permission.' . $row['id'] . '.own', array(
												'type' => 'checkbox', 
												'default' => $row['own'],
												'value' => 1,
												'class' => 'own'
											)) ?>
										<?php endif ?>
									</td>
									<td>
										<?php if ($row['any'] != 2): ?>
											<?= $this->Form->input('Permission.' . $row['id'] . '.any', array(
												'type' => 'checkbox', 
												'default' => $row['any'],
												'value' => 1,
												'class' => 'any'
											)) ?>
										<?php endif ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			<?php endforeach ?>

			<div class="clearfix"></div>

			<?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
			<?= $this->Form->hidden('old_defaults', array('value' => $this->request->data['Role']['defaults'])) ?>
			<?= $this->Form->hidden('id') ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	</div>

	<div class="tab-pane" id="permission">
		<?= $this->Form->create('Permission', array('action' => 'add', 'class' => 'well')) ?>
			<h2>Add Permission</h2>

			<?= $this->Form->input('plugin') ?>
			<?= $this->Form->input('controller') ?>
			<?= $this->Form->input('action') ?>
			<?= $this->Form->input('status', array(
				'value' => 1,
				'type' => 'checkbox'
			)) ?>

			<?= $this->Form->input('own', array(
				'value' => 1,
				'type' => 'checkbox'
			)) ?>
			<?= $this->Form->input('any', array(
				'value' => 1,
				'type' => 'checkbox'
			)) ?>

			<?= $this->Form->input('related', array(
				'type' => 'textarea'
			)) ?>

			<?= $this->Form->input('module_id', array(
				'empty' => '- choose -',
				'options' => $this->request->data['Modules']
			)) ?>
			<?= $this->Form->hidden('role_id', array('value' => $this->request->data['Role']['id'])) ?>

		<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn'
		)) ?>
	</div>
</div>