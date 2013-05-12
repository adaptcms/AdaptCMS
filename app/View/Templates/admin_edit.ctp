<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Template', null) ?>

<?php $this->CodeMirror->editor('TemplateTemplate') ?>

<h2 class="left">Edit Template</h2>

<div class="right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Template']['id'], $this->request->data['Template']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this template?')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('Template', array('class' => 'well admin-validate')) ?>
	
	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required', 
		'label' => 'File Name'
	)) ?>
	<?= $this->Form->input('label', array('type' => 'text')) ?>

	<?= $this->Form->input('template', array(
		'rows' => 25, 
		'style' => 'width:90%', 
		'class' => 'required',
		'value' => $template_contents
	)) ?>
	<?= $this->Form->input('theme_id', array(
	    'empty' => '- Choose -',
	    'class' => 'required'
	)) ?>
	<?= $this->Form->input('location', array(
		'options' => $locations,
		'empty' => '- Choose -',
		'class' => 'required',
		'value' => $location
	)) ?>

	<?= $this->Form->hidden('old_location', array(
		'value' => $this->request->data['Template']['location']
		)
	) ?>
	<?= $this->Form->hidden('old_theme', array(
		'value' => $this->request->data['Template']['theme_id']
		)
	) ?>
	<?= $this->Form->hidden('old_title', array(
		'value' => $this->request->data['Template']['title']
		)
	) ?>
	<?= $this->Form->hidden('modified', array(
		'value' => $this->Admin->datetime()
	)) ?>
	<?= $this->Form->hidden('id') ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>