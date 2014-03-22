<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins',
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Sample Items', null) ?>

<h1>Sample<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
<div class="btn-group pull-right">
    <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        View <i class="fa fa-picture-o"></i>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu view">
        <li>
            <?= $this->Html->link('<i class="fa fa-check"></i> Active', array(
                'admin' => true,
                'action' => 'index'
            ), array('escape' => false)) ?>
        </li>
        <li>
            <?= $this->Html->link('<i class="fa fa-trash-o"></i> Trash', array(
                'admin' => true,
                'action' => 'index',
                'trash' => 1
            ), array('escape' => false)) ?>
        </li>
    </ul>
</div>
<div class="clearfix"></div>

<?php if ($this->Admin->hasPermission($permissions['related']['sample']['admin_add'])): ?>
    <?= $this->Html->link('Add Sample <i class="fa fa-plus"></i>', array('action' => 'add'), array(
        'class' => 'btn btn-info pull-right',
        'style' => 'margin-bottom:10px',
        'escape' => false
    )) ?>
<?php endif ?>

<?php if (empty($this->request->data)): ?>
    <div class="well">
        No Items Found
    </div>
<?php else: ?>
	<div class="table-responsive">
	    <table class="table table-striped">
	        <thead>
	        <tr>
	            <th><?= $this->Paginator->sort('title') ?></th>
	            <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
	            <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	            <th></th>
	        </tr>
	        </thead>
	        <tbody>
		        <?php foreach ($this->request->data as $data): ?>
		            <tr>
		                <td>
		                    <?php if ($this->Admin->hasPermission($permissions['related']['sample']['admin_edit'], $data['User']['id'])): ?>
		                        <?= $this->Html->link($data['Sample']['title'], array(
		                            'action' => 'admin_edit',
		                            $data['Sample']['id']
		                        )) ?>
		                    <?php else: ?>
		                        <?= $data['Sample']['title'] ?>
		                    <?php endif ?>
		                </td>
		                <td>
		                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
		                        <?= $this->Html->link($data['User']['username'], array(
		                            'controller' => 'users',
		                            'action' => 'profile',
		                            $data['User']['username']
		                        )) ?>
		                    <?php endif ?>
		                </td>
		                <td class="hidden-xs">
		                    <?= $this->Admin->time($data['Sample']['created']) ?>
		                </td>
		                <td>
		                    <div class="btn-group">
		                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
		                            Actions
		                            <span class="caret"></span>
		                        </a>
		                        <ul class="dropdown-menu">
		                            <?php if (empty($this->request->named['trash'])): ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['sample']['admin_edit'], $data['User']['id'])): ?>
		                                    <li>
		                                        <?= $this->Admin->edit(
		                                            $data['Sample']['id']
		                                        ) ?>
		                                    </li>
		                                <?php endif ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['sample']['admin_edit'], $data['User']['id'])): ?>
		                                    <li>
		                                        <?= $this->Admin->delete(
		                                            $data['Sample']['id'],
		                                            $data['Sample']['title'],
		                                            'sample item'
		                                        ) ?>
		                                    </li>
		                                <?php endif ?>
		                            <?php else: ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['sample']['admin_edit'], $data['User']['id'])): ?>
		                                    <li>
		                                        <?= $this->Admin->restore(
		                                            $data['Sample']['id'],
		                                            $data['Sample']['title']
		                                        ) ?>
		                                    </li>
		                                <?php endif ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['sample']['admin_edit'], $data['User']['id'])): ?>
		                                    <li>
		                                        <?= $this->Admin->delete_perm(
		                                            $data['Sample']['id'],
		                                            $data['Sample']['title'],
		                                            'sample item'
		                                        ) ?>
		                                    </li>
		                                <?php endif ?>
		                            <?php endif ?>
		                        </ul>
		                    </div>
		                </td>
		            </tr>
		        <?php endforeach ?>
	        </tbody>
	    </table>
	</div>
<?php endif ?>

<?= $this->element('admin_pagination') ?>