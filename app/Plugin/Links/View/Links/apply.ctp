<?php $this->TinyMce->editor(array('simple' => true)) ?>

<?php $this->set('title_for_layout', 'Submit Link') ?>

<?php $this->Html->addCrumb('Links', null) ?>
<?php $this->Html->addCrumb('Submit Link', null) ?>

<?= $this->Form->create('Link', array('class' => 'well admin-validate')) ?>
    <h2>Submit Link</h2>

    <?= $this->Form->input('title', array('class' => 'required')) ?>
    <?= $this->Form->input('url', array(
        'class' => 'required url',
        'label' => 'Website Address',
        'placeholder' => 'http://'
    )) ?>
    <?= $this->Form->input('link_title') ?>
    <?= $this->Form->hidden('link_target', array('value' => '_blank')) ?>

    <?= $this->Form->input('image_url', array(
        'label' => 'Image URL (optional)',
        'class' => 'url',
        'placeholder' => 'http://'
    )) ?>

    <?php if (!empty($captcha)): ?>
        <div id="captcha">
            <?= $this->Captcha->form() ?>
        </div>
    <?php endif ?>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary'
)) ?>