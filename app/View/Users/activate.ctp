<h2>Activate Account</h2>

<?php
    echo $this->Form->create('User');
    echo $this->Form->input('username', array('type' => 'text', 'class' => 'required'));
    echo $this->Form->input('activate_code', array('type' => 'text', 'class' => 'required'));

    echo $this->Form->end('Submit');
?>