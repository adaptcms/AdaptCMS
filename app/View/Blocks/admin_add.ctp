<?php
    $this->CodeMirror->editor('BlockCode');
    $this->TinyMce->editor(array(
        'mode' => 'exact',
        'elements' => 'BlockText'
    ));
?>
<?= $this->Html->script('data-tagging.js') ?>

<?= $this->Form->create('Block', array('class' => 'well', 'id' => 'BlockAdminForm')) ?>
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
                    'options' => array(
                        'id' => 'ID',
                        'title' => 'Title',
                        'created' => 'Created',
                        'modified' => 'Modified',
                        'rand' => 'Random'
                    ),
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
            <?= $this->Form->input('code', array('style' => 'width:80%;height: 300px')) ?>
        </div>

        <div class="text-block">
            <?= $this->Form->input('text', array('style' => 'width:80%;height: 300px')) ?>
        </div>

    	<div class="input radio">
    	    <?= $this->Form->label('location_type', 'Location') ?>
    	    <?= $this->Form->radio('location_type', array(
    	            '*' => 'Global Public',
                    '#' => 'Global Admin',
    	            'view' => 'Specific View'
    	        ), array('legend' => false, 'hiddenField' => false, 'class' => 'required'
    	    )) ?>
    	</div>

    	<div id="location" style="display:none">
    		<div id="location_controller_component" style="display:none"></div>
    		<?= $this->Form->input('location_plugin', array(
    				'type' => 'select',
    				'div' => array('id' => 'location_plugin', 'style' => 'display:none')
    		)) ?>
    		<div style="margin-left:35%" id="locations"></div>
        	<?= $this->Form->input('location_controller', array(
        			'required' => true,
        			'type' => 'select',
        			'empty' => '- choose controller -',
        			'div' => array('id' => 'location_controller'),
        			'label' => false
        	)) ?>
        	<?= $this->Form->input('location_action', array(
        			'required' => true,
        			'type' => 'select',
        			'empty' => '- choose action -',
        			'div' => array('id' => 'location_action', 'style' => 'display:none'),
        			'label' => false
        	)) ?>
        	<?= $this->Form->input('location_id', array(
        			'empty' => '- choose entry (optional) -',
        			'div' => array('id' => 'location_id', 'style' => 'display:none'),
        			'label' => false
        	)) ?>
        	<?= $this->Form->button('Add', array(
        			'class' => 'btn btn-info', 
        			'id' => 'location_add',
        			'type' => 'button'
        	)) ?>

        	<div class="clearfix"></div>
    	</div>

    	<div class="clearfix"></div>

    	<?= $this->Form->hidden('module_id') ?>
        <?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>
    </div>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary'
)) ?>