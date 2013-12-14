<?php if ($this->Admin->hasPermission($permissions['related']['comments']['ajax_post'])): ?>
    <?php if (empty($this->request->data['Article']['settings']->comments_status) || $this->request->data['Article']['settings']->comments_status == 'open'): ?>
        <?= $this->Form->create('Comment', array('class' => 'PostComment admin-validate')) ?>

			<?= $this->Form->input('author_name', array(
				'label' => 'Your Name',
				'class' => 'author_name',
				'value' => ($this->Session->check('Comment.author_name') ? $this->Session->read('Comment.author_name') : '')
			)) ?>
			<?= $this->Form->input('author_email', array(
				'label' => 'Your Email Address',
				'class' => 'email author_email',
				'value' => ($this->Session->check('Comment.author_email') ? $this->Session->read('Comment.author_email') : ($this->Session->check('Auth.User.email') ? $this->Session->read('Auth.User.email') : ''))
			)) ?>
			<?= $this->Form->input('author_website', array(
				'label' => 'Your Website',
				'class' => 'url author_website',
				'placeholder' => 'http://',
				'value' => ($this->Session->check('Comment.author_website') ? $this->Session->read('Comment.author_website') : '')
			)) ?>

			<?php if (!empty($fields)): ?>
				<?php foreach($fields as $key => $field): ?>
					<?= $this->Element('FieldTypes/' . $field['FieldType']['slug'], array(
						'model' => 'ModuleValue',
						'key' => $key,
						'field' => $field,
						'icon' => !empty($field['Field']['description']) ?
							"<i class='icon icon-question-sign field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;" : ''
					)) ?>
					<?= $this->Form->hidden('ModuleValue.' . $key . '.field_id', array('value' => $field['Field']['id'])) ?>
					<?= $this->Form->hidden('ModuleValue.' . $key . '.module_id', array('value' => $this->request->data['User']['id'])) ?>
					<?= $this->Form->hidden('ModuleValue.' . $key . '.module_name', array('value' => 'comment')) ?>

					<?php if (!empty($field['ModuleValue'][0]['id'])): ?>
						<?= $this->Form->hidden('ModuleValue.' . $key . '.id', array('value' => $field['ModuleValue'][0]['id'])) ?>
					<?php endif ?>
				<?php endforeach ?>
			<?php endif ?>

            <?= $this->Form->input('comment_text', array(
                'class' => 'span6',
                'div' => array(
                    'style' => 'margin-bottom: 10px;margin-top: 10px'
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
                'type' => 'button',
                'class' => 'btn submit-comment'
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