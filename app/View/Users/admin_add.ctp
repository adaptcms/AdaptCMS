<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Users', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add User', null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<?= $this->Form->create('User', array('type' => 'file', 'class' => 'well admin-validate')) ?>
    <h2>Add User</h2>

    <?= $this->Form->input('username', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('password', array('type' => 'password', 'class' => 'required')) ?>
    <?= $this->Form->input('password_confirm', array('type' => 'password', 'class' => 'required')) ?>
    <?= $this->Form->input('email', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('role_id', array(
        'empty' => '- choose -',
        'class' => 'required'
    )) ?>

    <?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>
    <?= $this->Form->hidden('last_reset_time', array('value' => $this->Admin->datetime() )) ?>

    <?php if (!empty($this->request->data['SecurityQuestions']['SettingValue']['data'])): ?>
        <?php if (!empty($security_options)): ?>
            <?= $this->Form->input('security_question_hidden', array(
                    'options' => $security_options,
                    'label' => false,
                    'div' => false,
                    'style' => 'display:none'
            )) ?>
            <?php for($i = 1; $i <= $this->request->data['SecurityQuestions']['SettingValue']['data']; $i++): ?>
                <?= $this->Form->input('Security.'.$i.'.question', array(
                        'empty' => '- choose -',
                        'class' => 'required security-question',
                        'options' => $security_options,
                        'label' => 'Security Question '.$i
                )) ?>
                <div id="Security<?= $i ?>Question" style="display: none">
                    <?= $this->Form->input('Security.'.$i.'.answer', array(
                            'class' => 'required',
                            'label' => 'Security Answer '.$i
                    )) ?>
                </div>
            <?php endfor ?>
        <?php endif ?>
    <?php endif ?>

    <?= $this->Form->input('theme_id', array(
        'label' => 'Theme',
        'empty' => '- Choose Theme -'
    )) ?>

    <?= $this->Form->input('User.settings.time_zone', array(
        'label' => 'Timezone',
        'empty' => '- Choose -',
        'options' => $timezones
    )) ?>

    <?= $this->Form->input('User.settings.avatar', array(
        'label' => 'Avatar',
        'type' => 'file'
    )) ?>

    <?= $this->Form->input('status', array(
        'options' => array(
            'In-Active',
            'Active'
        ),
        'value' => 1
    )) ?>

    <?php foreach($fields as $key => $field): ?>
        <?= $this->Element('FieldTypes/' . $field['FieldType']['slug'], array(
            'model' => 'ModuleValue',
            'key' => $key,
            'field' => $field,
            'icon' => !empty($field['Field']['description']) ?
            "<i class='icon icon-question-sign field-desc' data-content='".$field['Field']['description']."' data-title='".$field['Field']['label']."'></i>&nbsp;" : ''
        )) ?>
        <?= $this->Form->hidden('ModuleValue.' . $key . '.field_id', array('value' => $field['Field']['id'])) ?>
        <?= $this->Form->hidden('ModuleValue.' . $key . '.module_name', array('value' => 'user')) ?>
    <?php endforeach ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>