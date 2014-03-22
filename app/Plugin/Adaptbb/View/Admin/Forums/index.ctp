<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Forums', null) ?>

<div class="pull-left">
    <h1>Forums<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
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

<div class="pull-right admin-edit-options">
    <!--nocache-->
    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_add'])): ?>
        <?= $this->Html->link('Add Forum <i class="fa fa-plus"></i>', array('action' => 'add'), array(
            'class' => 'btn btn-info', 
            'escape' => false
        )) ?>
    <?php endif ?>
    <!--/nocache-->
    <?= $this->Html->link('Manage Categories <i class="fa fa-th-list"></i>', array('controller' => 'forum_categories'), array(
        'class' => 'btn btn-success',
        'escape' => false
    )) ?>
</div>
<div class="clearfix"></div>

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
	                <th><?= $this->Paginator->sort('ForumCategory.title', 'Category') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('User.username', 'Author') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td>
	                        <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_edit'], $data['User']['id'])): ?>
	                            <?= $this->Html->link($data['Forum']['title'], array(
	                                'action' => 'edit',
	                                $data['Forum']['id']
	                            )) ?>
	                        <?php else: ?>
	                            <?= $data['Forum']['title'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <?php if ($this->Admin->hasPermission($permissions['related']['forum_categories']['admin_edit'], $data['ForumCategory']['id'])): ?>
	                            <?= $this->Html->link($data['ForumCategory']['title'], array(
	                                'action' => 'edit',
	                                'controller' => 'forum_categories',
	                                $data['ForumCategory']['id']
	                            )) ?>
	                        <?php else: ?>
	                            <?= $data['ForumCategory']['title'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
	                            <?= $this->Html->link($data['User']['username'], array(
	                                'controller' => 'users',
	                                'action' => 'profile',
	                                $data['User']['username']
	                            )) ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?= $this->Admin->time($data['Forum']['created']) ?>
	                    </td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <?php if (empty($this->request->named['trash'])): ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_edit'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Forum']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Forum']['id'],
	                                                $data['Forum']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php else: ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_restore'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->restore(
	                                                $data['Forum']['id'],
	                                                $data['Forum']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Forum']['id'],
	                                                $data['Forum']['title'],
	                                                true
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