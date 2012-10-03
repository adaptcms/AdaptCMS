<h1>Login</h1>
<?php
    echo $this->Form->create();
    echo $this->Form->inputs(
        array(
            'username',
            'password'
        )
    );
    echo $this->Form->end('Submit');
    echo $this->Html->link('Don\'t have an account? Register now!', array(
        'action' => 'register'
    ));
    ?>
<br />
<?php
    echo $this->Html->link('Forgot Password', array(
        'action' => 'update_password',
        'change' => 'forgot'
    ));
?>