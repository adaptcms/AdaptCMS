<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Template', null) ?>

<?php
	$this->CodeMirror->editor('TemplateTemplate');
?>

<script>
$(document).ready(function(){
    $("#TemplateThemeId").live('change', function() {
        var theme = $("#TemplateThemeId option:selected");
        var empty = $("#TemplateLocation option[value='']");

        if ($(theme).val()) {
	        $.post("<?= $this->webroot ?>ajax/templates/template_locations/", 
	        {
	            data:{
	                Theme:{
	                    id: $(theme).val(),
	                    title: $(theme).html()
	                }
	            }
	        }, function(data) {
	        	$("#TemplateLocation option").remove();
	        	$("#TemplateLocation").append(data).prepend(empty);
	        });
	    } else {
	    	$("#TemplateLocation option").remove();
	    }
    });
});
</script>

<h2 class="left">Edit Template</h2>

<div class="right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Template']['id'], $this->request->data['Template']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this template?')"));
    ?>
</div>
<div class="clearfix"></div>

<?php
	echo $this->Form->create('Template', array('class' => 'well admin-validate'));
	
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->input('template', array(
		'rows' => 25, 
		'style' => 'width:90%', 
		'class' => 'required',
		'value' => $template_contents
	));
	echo $this->Form->input('theme_id', array(
	    'empty' => '- Choose -',
	    'class' => 'required'
	));
	echo $this->Form->input('location', array(
		'options' => $locations,
		'empty' => '- Choose -',
		'class' => 'required',
		'value' => $location
	));

	echo $this->Form->hidden('old_location', array(
		'value' => $this->request->data['Template']['location']
		)
	);
	echo $this->Form->hidden('old_theme', array(
		'value' => $this->request->data['Template']['theme_id']
		)
	);
	echo $this->Form->hidden('old_title', array(
		'value' => $this->request->data['Template']['title']
		)
	);
	echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
	echo $this->Form->hidden('id');
?>
<br />
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>