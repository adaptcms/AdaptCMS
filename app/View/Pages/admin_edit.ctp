<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Pages', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Page', null) ?>

<?php
	$this->CodeMirror->editor('PageContent');
?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
    <li class="active">
        <a href="#page" data-toggle="tab">Edit Page</a>
    </li>
    <?php if (!empty($docs)): ?>
        <li>
            <a href="#docs" data-toggle="tab">Documentation</a>
        </li>
    <?php endif ?>
    <div class="pull-right hidden-phone">
        <?= $this->Html->link(
            '<i class="icon-chevron-left"></i> Return to Index',
            array('action' => 'index'),
            array('class' => 'btn', 'escape' => false
            )) ?>
        <?= $this->Html->link(
            '<i class="icon-trash icon-white"></i> Delete',
            array('action' => 'delete', $this->request->data['Page']['id'], $this->request->data['Page']['title']),
            array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this page?')"));
        ?>
    </div>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="page">
        <?= $this->Form->create('Page', array('class' => 'well admin-validate')) ?>
            <h2>Edit Page</h2>

            <?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
            <?= $this->Form->hidden('old_title', array('value' => $this->request->data['Page']['title'])) ?>
            <?= $this->Form->input('content', array(
                'rows' => 25,
                'style' => 'width:80%',
                'class' => 'required'
            )) ?>

            <?= $this->Form->hidden('id') ?>

        <?php if ($writable == 1): ?>
            <?= $this->Form->end(array(
                'label' => 'Submit',
                'class' => 'btn btn-primary'
            )) ?>
        <?php else: ?>
            <?= $this->Element('writable_notice', array(
                'type' => 'template',
                'file' => $writable
            )) ?>
            <?= $this->Form->end() ?>
        <?php endif ?>
    </div>
    <?php if (!empty($docs)): ?>
        <div class="tab-pane" id="docs">
            <div class="well">
                <h2>Documentation</h2>

                <?= $docs ?>
            </div>
        </div>
    <?php endif ?>
</div>