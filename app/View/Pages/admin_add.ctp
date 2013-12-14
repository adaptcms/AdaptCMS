<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Pages', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Page', null) ?>

<?php $this->CodeMirror->editor('PageContent') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
    <li class="active">
        <a href="#page" data-toggle="tab">Add Page</a>
    </li>
    <?php if (!empty($docs)): ?>
        <li>
            <a href="#docs" data-toggle="tab">Documentation</a>
        </li>
    <?php endif ?>
</ul>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="page">
        <?= $this->Form->create('Page', array('class' => 'well admin-validate')) ?>
            <h2>Add Page</h2>

            <?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
            <?= $this->Form->input('content', array('style' => 'width:80%;height: 300px', 'class' => 'required')) ?>

        <?= $this->Form->end(array(
            'label' => 'Submit',
            'class' => 'btn btn-primary'
        )) ?>
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