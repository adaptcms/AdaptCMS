<script>
$(document).ready(function(){
	$("#backButton").live('click', function(e) {
		e.preventDefault();
		
		window.location = "<?= $this->Html->url(array(
            'controller' => 'modules',
            'action' => 'add',
            'admin' => true
            )) ?>";
		return false;
	});
});
</script>

<h1>Add Module - Step Two</h1>
<?php
    echo $this->Form->create('Module', array('class' => 'well', 'action' => 'step_three'));
    
    echo $this->Form->input('data', array(
    	'type' => 'select', 
    	'class' => 'required',
    	'empty' => '- Choose -',
    	'options' => $list
    ));

    echo $this->Form->input('location');

    for ($i = 1; $i <= 2; $i++) {
        echo $this->Form->input($i.'.template', array(
            'type' => 'select',
            'class' => 'required',
            'empty' => '- Choose -',
            'options' => $templates
        ));
    }
    
    echo $this->Form->button('Back', array('id' => 'backButton', 'style' => 'float: left;margin-right: 10px'));
    echo $this->Form->end('Continue', array('style' => 'float:left'));
?>