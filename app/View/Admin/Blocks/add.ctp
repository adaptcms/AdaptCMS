<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Blocks', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Block', null) ?>

<?php
    $this->CodeMirror->editor('BlockCode');
    $this->TinyMce->editor(array('selector' => '#BlockText'));
?>
<?= $this->Html->script('data-tagging.js') ?>

<?= $this->Form->create('Block', array('class' => 'well', 'id' => 'BlockAdminForm')) ?>
    <p class="pull-right span5">
        <small>
            If you add a dynamic data block and choose a specific view for it to appear - you must select a feature and then a view. An example is selecting the 'Article' feature and picking the view 'view'. The data is then accessible in the layout/that features templates.
        </small>
    </p>

    <h2>Add Block</h2>
    
    <?= $this->Form->input('type', array(
        'label' => 'Type of Block',
        'options' => $block_types,
        'empty' => '- choose -',
        'class' => 'required'
    )) ?>

    <div id="dynamic" style="display: none">
        <?= $this->Form->input('model', array(
            'type' => 'select', 
            'class' => 'required',
            'empty' => '- Choose -',
            'options' => $models
        )) ?>
    </div>

    <div id="next-step" style="display:none">
        <?= $this->Form->input('title', array(
            'label' => 'Name of Block',
            'class' => 'required'
        )) ?>

        <div class="dynamic">
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

            <div id="custom-data"></div>
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
    </div>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary'
)) ?>