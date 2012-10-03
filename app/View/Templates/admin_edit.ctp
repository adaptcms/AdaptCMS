<?php
	$this->EditArea->editor('TemplateTemplate');
?>

<script>
$(document).ready(function(){
    $("#TemplateAdminEditForm").validate();
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

<h1>Edit Template</h1>

<?php
	echo $this->Form->create('Template', array('class' => 'well'));
	
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
	echo $this->Form->hidden('modified');
	echo $this->Form->hidden('id');
?>
<br />
<?= $this->Form->end(array(
		'label' => 'Submit',
		'class' => 'btn'
		)); ?>