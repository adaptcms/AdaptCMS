<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Sample Items', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Sample', null) ?>

<?php $this->TinyMce->editor() ?>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Sample']['id'], $this->request->data['Sample']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this sample item?')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('Sample', array('class' => 'well admin-validate')) ?>
    <h2>Edit Sample</h2>

    <?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('text', array('type' => 'textarea', 'class' => 'required')) ?>

    <?= $this->Form->hidden('id') ?>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary'
)) ?>