<?php if ($this->Admin->hasPermission($permissions['related']['comments']['ajax_post'])): ?>
    <?php if (empty($this->request->data['Article']['settings']->comments_status) || $this->request->data['Article']['settings']->comments_status == 'open'): ?>
        <?= $this->Form->create('Comment', array('class' => 'PostComment')) ?>

            <?= $this->Form->input('comment_text', array(
                'class' => 'span6',
                'div' => array(
                    'style' => 'margin-bottom: 10px'
                ),
                'label' => false,
                'placeholder' => 'Enter in your comment...'
            )) ?>

            <?php if (!empty($this->request->data['Article']['id'])): ?>
                <?= $this->Form->hidden('article_id', array(
                    'value' => $this->request->data['Article']['id']
                )) ?>
            <?php endif ?>

            <?php if (!empty($captcha_setting)): ?>
                <div id="captcha">
                    <?= $this->Captcha->form() ?>
                </div>
            <?php endif ?>

            <?= $this->Form->button('Post Comment', array(
                'type' => 'submit',
                'class' => 'btn'
            )) ?>

        <?= $this->Form->end() ?>
    <?php endif ?>
<?php elseif(!$this->Session->check('Auth.User.username')): ?>
    Please <?= $this->Html->link('login',
        array(
            'plugin' => null,
            'controller' => 'users',
            'action' => 'login'
        )) ?> or
    <?= $this->Html->link('register',
        array(
            'plugin' => null,
            'controller' => 'users',
            'action' => 'register'
        )) ?> in order to post a comment.
<?php endif ?>