<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Polls', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Poll', null) ?>

<?= $this->Html->script('Polls.admin.js') ?>
<?= $this->Html->css('Polls.admin.css') ?>

<h2 class="left">Edit Poll</h2>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Poll']['id'], $this->request->data['Poll']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this poll?')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('Poll', array('class' => 'well admin-validate')) ?>
	<?= $this->Form->input('title', array(
        'type' => 'text', 
        'class' => 'required'
    )) ?>
    <?= $this->Form->input('article_id', array(
        'label' => 'Attach to Article', 
        'empty' => ' - choose - '
    )) ?>

    <div id="options">
        <?php foreach($this->request->data['PollValue'] as $key => $data): ?>
            <div id='option<?= $key ?>'>
            	<div class='input text'>
                	<?= $this->Form->input('PollValue.' . $key . '.title', array(
                		'label' => 'Option '.$key,
                		'value' => $data['title'],
                		'class' => 'required option pull-left',
                		'div' => false
                	)) ?>

                  <?= $this->Form->button('<i class="icon-trash icon-white poll-delete"></i> Delete', array(
                     'type' => 'button',
                     'class' => 'btn btn-danger poll-remove pull-right',
                     'div' => false
                  )) ?>
               </div>
               <div class="clearfix"></div>

               <?= $this->Form->input('PollValue.' . $key . '.id', array(
                  'value' => $data['id']
               )) ?>
               <?= $this->Form->hidden('PollValue.' . $key . '.delete', array(
                  'value' => 0, 
                  'class' => 'delete'
               )) ?>
            </div>
        <?php endforeach ?>
    </div>
    <div class="clearfix"></div>

    <?= $this->Form->hidden('modified', array('value' => $this->Admin->datetime() )) ?>
    <?= $this->Form->input('id', array('type' => 'hidden')) ?>

    <div class="btn-group">
        <?= $this->Form->button('Add Option', array(
            'type' => 'button',
            'id' => 'poll-option-add',
            'class' => 'btn btn-warning'
        )) ?>
        <?= $this->Form->end(array(
            'label' => 'Submit',
            'class' => 'btn btn-primary',
            'div' => false
        )) ?>
    </div>