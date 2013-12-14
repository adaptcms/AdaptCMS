<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Blocks', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Block', null) ?>

<?php
    $this->CodeMirror->editor('BlockCode');
    $this->TinyMce->editor(array('selector' => '#BlockText'));
?>
<?= $this->Html->script('data-tagging.js') ?>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Block']['id'], $this->request->data['Block']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this block?')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('Block', array('class' => 'well', 'id' => 'BlockAdminForm')) ?>
    <p class="pull-right span5">
        <small>
            If you add a dynamic data block and choose a specific view for it to appear - you must select a feature and then a view. An example is selecting the 'Article' feature and picking the view 'view'. The data is then accessible in the layout/that features templates.
        </small>
    </p>

    <h2>Edit Block</h2>

    <?= $this->Form->input('type', array(
        'label' => 'Type of Block',
        'options' => $block_types,
        'empty' => '- choose -',
        'class' => 'required'
    )) ?>

    <?php if (!empty($this->request->data['Block']['data']) && is_numeric($this->request->data['Block']['data'])): ?>
        <span id="BlockDataHidden" class="invisible"><?= $this->request->data['Block']['data'] ?></span>
    <?php endif ?>

    <div id="dynamic" style="display: none">
        <?= $this->Form->input('model', array(
            'type' => 'select', 
            'class' => 'required',
            'empty' => '- Choose -',
            'options' => $models
        )) ?>
    </div>

<div id="next-step">
    <?= $this->Form->input('title', array(
        'label' => 'Name of Block',
        'class' => 'required'
    )) ?>

    <div class="dynamic">
	    <?= $this->Form->hidden('order_by_hide', array('value' => (!empty($this->request->data['Block']['order_by']) ? $this->request->data['Block']['order_by'] : '') )) ?>

        <?= $this->Form->input('order_by', array(
                'empty' => '- Choose -',
                'options' => array(),
                'div' => array(
                    'style' => 'display: none',
                    'class' => 'order'
                )
        )) ?>

        <?= $this->Form->input('order_dir', array(
                'options' => array(
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ),
                'div' => array(
                    'style' => 'display: none',
                    'class' => 'order'
                )
        )) ?>

    	<?= $this->Form->input('limit', array(
    	        'label' => "How many <strong></strong> to display?",
    	        'empty' => '- Choose -',
    	        'options' => $limit
    	)) ?>

    	<div id="data" style="display: none">
    	    <?= $this->Form->input('data', array(
    	            'type' => 'select', 
    	            'empty' => '- Choose -'
    	    )) ?>
    	</div>

        <div id="custom-data" style="display: none">
            <?php if (!empty($this->request->data['Block']['settings_keys'])): ?>
                {
                <?php foreach($this->request->data['Block']['settings_keys'] as $key): ?>
                    "<?= $key ?>": "<?= $this->request->data['Block'][$key] ?>"
                <?php endforeach ?>
                }
            <?php endif ?>
        </div>
    </div>

    <div class="code-block">
        <?= $this->Form->input('code', array(
            'type' => 'textarea', 
            'style' => 'width:80%;height: 300px'
        )) ?>
    </div>

    <div class="text-block">
        <?= $this->Form->input('text', array(
            'type' => 'textarea',
            'style' => 'width:80%;height: 300px'
        )) ?>
    </div>
	<div class="clearfix"></div>

	<?= $this->Form->hidden('module_id') ?>

	<div class="btn-group" style="margin-top:10px">
        <?= $this->Form->hidden('id') ?>

        <?= $this->Form->end(array(
            'label' => 'Submit',
            'class' => 'btn btn-primary'
        )) ?>
	</div>
</div>