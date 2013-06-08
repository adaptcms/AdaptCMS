<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Field Types', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Field Type', null) ?>

<?php $this->CodeMirror->editor(array(
    'FieldTypeTemplate',
    'FieldTypeDataTemplate'
)) ?>

<?= $this->Form->create('FieldType', array('class' => 'well admin-validate')) ?>
	<h2>Edit Field Types</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('label', array('type' => 'text')) ?>

    <?= $this->Form->input('limit', array(
        'label' => 'Does type make use of a min/max char limit?',
        'type' => 'checkbox'
    )) ?>
    <?= $this->Form->input('template', array(
        'label' => 'Template',
        'type' => 'textarea',
        'style' => 'width:80%;height: 300px'
    )) ?>
    <?= $this->Form->input('data_template', array(
        'label' => 'Data Template',
        'type' => 'textarea',
        'style' => 'width:80%;height: 300px'
    )) ?>
    <?= $this->Form->input('active', array(
        'label' => 'Enabled?',
        'type' => 'checkbox'
    )) ?>

	<?= $this->Form->hidden('id') ?>
	<?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>