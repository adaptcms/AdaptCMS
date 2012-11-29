<?php
	$this->TinyMce->editor();
?>

<script>
 $(document).ready(function(){
    $("#SettingEditForm").validate();
    $("#SettingValueAddForm").validate();
    $("#SettingValueEditForm").validate();

    $("#SettingValueSettingType").live('change', function() {
    	if ($("#SettingValueSettingType :selected").val()) {
    		var id = 'SettingValueData';
    		var field_type = $("#SettingValueSettingType :selected").val();

    		if (field_type == "textarea") {
    			$("#text").html('<?= $this->Form->input("SettingValue.data", array("class" => "required", "rows" => 15, "style" => "width:500px")) ?>');
    			tinyMCE.execCommand('mceAddControl', false, id);
    			$("#field_data").html(null);
    			$(".field_options").hide();
    			$(".input.text.clear").css("margin-top", "0");
    		} else if (field_type == "text") {
    			$("#text").html('<?= $this->Form->input("SettingValue.data", array("class" => "required", "type" => "text")) ?>');
    			$("#field_data").html(null);
    			$(".field_options").hide();
    			if (tinyMCE.getInstanceById(id)) {
				    tinyMCE.execCommand('mceFocus', false, id);                    
				    tinyMCE.execCommand('mceRemoveControl', false, id);
				}
    		} else if (field_type == "check" || field_type == "radio" || field_type == "dropdown") {
    			$(".field_options").show();
    			if (tinyMCE.getInstanceById(id)) {
				    tinyMCE.execCommand('mceFocus', false, id);                    
				    tinyMCE.execCommand('mceRemoveControl', false, id);
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
		<a href="#main" data-toggle="tab">Edit Settings Category</a>
	</li>
	<?php if (!empty($this->request->data['SettingValue'])): ?>
		<li>
			<a href="#update-settings" data-toggle="tab">Update Settings</a>
		</li>
	<?php endif ?>
	<li>
		<a href="#add-setting" data-toggle="tab">Add Setting</a>
	</li>
</ul>

<div class="right">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete Category',
        array('action' => 'delete', $this->request->data['Setting']['id'], $this->request->data['Setting']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this settings category?')"));
    ?>
</div>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('Setting', array('action' => 'edit', 'class' => 'well')) ?>
			<h2>Edit Settings Category</h2>

			<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>

			<?= $this->Form->hidden('created', array('type' => 'hidden')) ?>
		    <?= $this->Form->hidden('id') ?>

			<br />
		<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn'
		)) ?>
	</div>

	<?php if (!empty($this->request->data['SettingValue'])): ?>
		<div class="tab-pane" id="update-settings">
			<?= $this->Form->create('SettingValue', array('action' => 'edit/'.$this->request->data['Setting']['id'], 'id' => 'SettingValueEditForm', 'class' => 'well')) ?>
				<h2>Update Settings</h2>

				<?php foreach($this->request->data['SettingValue'] as $row): ?>
					<?= $this->Form->input('SettingValue.'.$row['id'].'.title', array('value' => $row['title'], 'class' => 'required')) ?>
					<?php if ($row['setting_type'] == "textarea"): ?>
							<?= $this->Form->input('SettingValue.'.$row['id'].'.data', array('value' => $row['data'], 'style' => 'width:500px', 'rows' => 15)) ?>
					<?php elseif ($row['setting_type'] == "text"): ?>
						<?= $this->Form->input('SettingValue.'.$row['id'].'.data', array('type' => 'text', 'value' => $row['data'])) ?>
					<?php elseif ($row['setting_type'] == "dropdown"): ?>
						<?php 
							$data_options = null;
							foreach(json_decode($row['data_options']) as $json) {
								$data_options[$json] = $json;
							}
						?>
						<?= $this->Form->input('SettingValue.'.$row['id'].'.data', array(
							'value' => $row['data'], 
							'options' => $data_options,
							'empty' => '- Choose -'
					)) ?>
					<?php endif; ?>
					<?= $this->Form->input('SettingValue.'.$row['id'].'.description', array(
						'value' => $row['description'], 
						'rows' => 15, 
						'style' => 'width:500px',
						'class' => 'required'
					)) ?><br />

					<?= $this->Form->hidden('SettingValue.'.$row['id'].'.id', array('value' => $row['id'])) ?>
					<?= $this->Form->hidden('SettingValue.'.$row['id'].'.modified', array(
							'value' => $this->Time->format('Y-m-d H:i:s', time())
					)) ?>
			 	<?php endforeach; ?>
				<br />
				
			<?= $this->Form->end(array(
					'label' => 'Submit',
					'class' => 'btn'
			)) ?>
		</div>
	<?php endif ?>

	<div class="tab-pane" id="add-setting">
		<?= $this->Form->create('SettingValue', array('action' => 'add', 'class' => 'well')) ?>
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
			<div class="field_options" style="margin-bottom: 9px">
					<?= $this->Form->input('field_options', array(
						'div' => false, 
						'style' => 'margin-bottom: 0',
						'type' => 'text'
					)) ?>
					<?= $this->Form->button('Add', array(
						'class' => 'btn btn-info', 
						'type' => 'button',
						'id' => 'add-data'
					)) ?>
			</div>
			<div id="field_data" style="width: 30%;margin-bottom: 9px"></div>

			<?= $this->Form->input('description', array(
				'rows' => 15, 'style' => 'width:500px', 'class' => 'required',
					'div' => array(
						'class' => 'input text clear',
						'style' => 'margin-top: -9px'
						)
			)) ?>
			<?= $this->Form->hidden('setting_id', array('value' => $this->request->data['Setting']['id'])) ?>
			<?= $this->Form->hidden('created', array('value' => $this->Time->format('Y-m-d H:i:s', time()))) ?>

			<br />
		<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn'
		)) ?>
	</div>
</div>