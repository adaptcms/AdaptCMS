<h1>Add Module</h1>
<?php
    echo $this->Form->create('Module', array('class' => 'well', 'action' => 'step_two'));
    
    echo $this->Form->input('model', array(
    	'type' => 'select', 
    	'class' => 'required',
    	'empty' => '- Choose -',
    	'options' => $models
    ));
    
    echo $this->Form->end('Continue');
?>