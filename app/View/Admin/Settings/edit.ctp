<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Settings', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Settings', null) ?>

<?php $this->TinyMce->editor() ?>

<script>
 $(document).ready(function(){
    $("#SettingValueSettingType").live('change', function() {
    	if ($("#SettingValueSettingType :selected").val()) {
    		var id = 'SettingValueData';
    		var field_type = $("#SettingValueSettingType :selected").val();

    		if (field_type == "textarea") {
    			$("#text").html('<?= $this->Form->input("SettingValue.data", array("class" => "required", "rows" => 15, "style" => "width:500px")) ?>');
    			tinyMCE.execCommand('mceAddEditor', false, id);
    			$("#field_data").html(null);
    			$(".field_options").hide();
    			$(".input.text.clear").css("margin-top", "0");
    		} else if (field_type == "text") {
    			$("#text").html('<?= $this->Form->input("SettingValue.data", array("class" => "required", "type" => "text")) ?>');
    			$("#field_data").html(null);
    			$(".field_options").hide();
    			if (typeof tinyMCE.editors[id] !== 'undefined') {
				    tinyMCE.execCommand('mceFocus', false, id);                    
				    tinyMCE.execCommand('mceRemoveEditor', false, id);
				}
    		} else if (field_type == "check" || field_type == "radio" || field_type == "dropdown") {
    			$(".field_options").show();
    			if (typeof tinyMCE.editors[id] !== 'undefined') {
				    tinyMCE.execCommand('mceFocus', false, id);                    
				    tinyMCE.execCommand('mceRemoveEditor', false, id);
				}
				$("#text").html(null);
    		} else {
    			$(".field_options").hide();
    		}
    	} else {
    		$(".field_options").html("");
    	}
    });
 });
 </script>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit Category</a>
	</li>
	<?php if (!empty($this->request->data['SettingValue'])): ?>
		<li>
			<a href="#update-settings" data-toggle="tab">Update Settings</a>
		</li>
	<?php endif ?>
	<li>
		<a href="#add-setting" data-toggle="tab">Add Setting</a>
	</li>
	<div class="right hidden-xs">
	    <?= $this->Html->link(
	        '<i class="fa fa-chevron-left"></i> Return to Index',
	        array('action' => 'index'),
	        array('class' => 'btn btn-info', 'escape' => false
	    )) ?>
	    <?= $this->Html->link(
	        '<i class="fa fa-trash-o"></i> Delete Category',
	        array('action' => 'delete', $this->request->data['Setting']['id'], $this->request->data['Setting']['title']),
	        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this settings category?')"));
	    ?>
	</div>
</ul>

<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('Setting', array('action' => 'edit', 'class' => 'well admin-validate')) ?>
			<h2>Edit Settings Category</h2>

			<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>

		    <?= $this->Form->hidden('id') ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	</div>

	<?php if (!empty($this->request->data['SettingValue'])): ?>
		<div class="tab-pane" id="update-settings">
			<?= $this->Form->create('SettingValue', array('action' => 'edit/'.$this->request->data['Setting']['id'], 'class' => 'well admin-validate')) ?>
				<h2>Update Settings</h2>

				<?php foreach($this->request->data['SettingValue'] as $key => $row): ?>
					<?= $this->Form->input($key . '.SettingValue.title', array('value' => $row['title'], 'class' => 'required')) ?>
					<?php if ($row['setting_type'] == "textarea"): ?>
						<?= $this->Form->input($key . '.SettingValue.data', array('value' => $row['data'], 'style' => 'width:500px', 'rows' => 15)) ?>
					<?php elseif ($row['setting_type'] == "text"): ?>
						<?= $this->Form->input($key . '.SettingValue.data', array('type' => 'text', 'value' => $row['data'])) ?>
					<?php elseif ($row['setting_type'] == "radio"): ?>
						<?= $this->Form->label($key . '.SettingValue.data', 'Data') ?>
						<div class="input radio">
						    <?= $this->Form->radio($key . '.SettingValue.data', array_combine($row['data_options'], $row['data_options']), array(
						        'legend' => false, 
						        'hiddenField' => false, 
						        'value' => !empty($row['data']) ? $row['data'] : ''
						    )) ?>
						</div>
					<?php elseif ($row['setting_type'] == "check"): ?>
					    <?= $this->Form->input($key . '.SettingValue.data', array(
					        'label' => 'Data', 
					        'multiple' => 'checkbox', 
					        'options' => array_combine($row['data_options'], $row['data_options']),
					        'value' => !empty($row['data']) ? $row['data'] : ''
					    )) ?>
					<?php elseif ($row['setting_type'] == "dropdown"): ?>
						<?= $this->Form->input($key . '.SettingValue.data', array(
							'value' => !empty($row['data']) ? $row['data'] : '',
							'options' => array_combine($row['data_options'], $row['data_options']),
							'empty' => '- Choose -'
						)) ?>
					<?php endif; ?>
					<?= $this->Form->input($key . '.SettingValue.description', array(
						'value' => $row['description'], 
						'rows' => 15
					)) ?>
					<?= $this->Form->input($key . '.SettingValue.deleted', array(
						'value' => 1,
						'type' => 'checkbox',
						'label' => 'Delete Setting?',
						'class' => 'delete'
					)) ?>

					<?= $this->Form->hidden($key . '.SettingValue.id', array(
						'value' => $row['id']
					)) ?>

					<div class="clearfix"></div><br />
			 	<?php endforeach; ?>
				
			<?= $this->Form->end(array(
					'label' => 'Submit',
					'class' => 'btn btn-primary'
			)) ?>
		</div>
	<?php endif ?>

	<div class="tab-pane" id="add-setting">
		<?= $this->Form->create('SettingValue', array('action' => 'add', 'class' => 'well admin-validate')) ?>
			<h2>Add New Setting</h2>

			<?= $this->Form->input('title', array('class' => 'required')) ?>
			<?= $this->Form->input('setting_type', array(
				'class' => 'required',
				'empty' => '- Choose -',
				'options' => array(
					'text' => 'Text Input', 
					'dropdown' => 'Dropdown Selector', 
					'check' => 'Checkbox', 
					'radio' => 'Radio', 
					'textarea' => 'Text Box'
					)
			)) ?>

			<div id="text"></div>
			<div class="field_options input-group col-lg-5">
				<?= $this->Form->label('field_options', "Field Options") ?>
				<div class="clearfix"></div>

				<?= $this->Form->input('field_options', array(
					'label' => false,
					'div' => false,
					'class' => 'form-control form-control-inline',
					'type' => 'text'
				)) ?>
				<?= $this->Form->button('Add', array(
					'class' => 'btn btn-info',
					'type' => 'button',
					'id' => 'add-data'
				)) ?>
			</div>
			<div id="field_data" class="clearfix" style="width: 30%;margin-bottom: 15px"></div>
			<div class="clearfix"></div>

			<?= $this->Form->input('description', array(
				'rows' => 15,
                'class' => 'required',
                'div' => array(
                    'class' => 'input text clearfix',
                    'style' => 'margin-top: -9px'
                )
			)) ?>
			<?= $this->Form->hidden('setting_id', array('value' => $this->request->data['Setting']['id'])) ?>

		<?= $this->Form->end(array(
			'label' => 'Submit',
			'class' => 'btn btn-primary'
		)) ?>
	</div>
</div>