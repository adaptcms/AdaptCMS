<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Templates', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Template', null) ?>

<?php $this->CodeMirror->editor('TemplateTemplate') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
    <li class="active">
        <a href="#template" data-toggle="tab">Edit Template</a>
    </li>
    <?php if (!empty($template_docs)): ?>
        <li>
            <a href="#docs" data-toggle="tab">Documentation</a>
        </li>
    <?php endif ?>
    <div class="pull-right hidden-xs">
        <?= $this->Html->link(
            '<i class="fa fa-chevron-left"></i> Return to Index',
            array('action' => 'index'),
            array('class' => 'btn btn-info', 'escape' => false
            )) ?>
        <?= $this->Html->link(
            '<i class="fa fa-trash-o"></i> Delete',
            array('action' => 'delete', $this->request->data['Template']['id'], $this->request->data['Template']['title']),
            array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this template?')"));
        ?>
    </div>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="template">
        <?= $this->Form->create('Template', array('class' => 'well admin-validate')) ?>
            <h2>Edit Template</h2>

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
    <?php if (!empty($template_docs)): ?>
        <div class="tab-pane" id="docs">
            <div class="well">
                <h2>Documentation</h2>

                <?= $template_docs ?>
            </div>
        </div>
    <?php endif ?>
</div>