<?php $this->Html->addCrumb('Login', null) ?>

<h1>Login</h1>

<?php
    echo $this->Form->create();

    echo $this->Form->input('username');
    echo $this->Form->input('password');

    echo $this->Form->end(array('class' => 'btn', 'label' => 'Login'));
    echo $this->Html->link("Don't have an account? Register now!", array(
        'action' => 'register'
    ));
    ?>
<br />
<?php
    echo $this->Html->link('Forgot Password', array(
        'action' => 'forgot_password'
    ));
?>

<?php if (!empty($this->Facebook)): ?>
    <h1>3rd Party Login</h1>

    <p>
        <?php if (!empty($this->Facebook)): ?>
            <?= $this->Facebook->login() ?>
            <?php if ($this->Session->check('Auth.User.login_type') && $this->Session->read('Auth.User.login_type') == "facebook"): ?>
                <br /><br />
                <?= $this->Facebook->logout(array('redirect' => array('action' => 'logout', 'controller' => 'users'), 'img' => 'facebook-logout.png')) ?>
            <?php endif ?>
        <?php endif ?>
    </p>
<?php endif ?>

<div class="clearfix"></div>