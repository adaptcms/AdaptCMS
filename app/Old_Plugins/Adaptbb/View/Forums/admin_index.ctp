<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Forums', null) ?>

<div class="pull-left">
    <h1>Forums<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group pull-right">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    View <i class="icon-picture"></i>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu view">
    <li>
        <?= $this->Html->link('<i class="icon-ok"></i> Active', array(
            'admin' => true, 
            'action' => 'index'
        ), array('escape' => false)) ?>
    </li>
    <li>
        <?= $this->Html->link('<i class="icon-trash"></i> Trash', array(
            'admin' => true, 
            'action' => 'index', 
            'trash' => 1
        ), array('escape' => false)) ?>
    </li>
  </ul>
</div>
<div class="clearfix"></div>

<div class="pull-right admin-edit-options">
    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_add'])): ?>
        <?= $this->Html->link('Add Forum <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
            'class' => 'btn btn-info', 
            'escape' => false
        )) ?>
    <?php endif ?>
    <?= $this->Html->link('Manage Forum Categories <i class="icon icon-th-list icon-white"></i>', array('controller' => 'forum_categories'), array(
        'class' => 'btn btn-success',
        'escape' => false
    )) ?>
</div>

<?php if (empty($this->request->data)): ?>
    <div class="clearfix"></div>
    <div class="well">
        No Items Found
    </div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('ForumCategory.title', 'Category') ?></th>
                <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>

        <?php foreach ($this->request->data as $data): ?>
            <tbody>
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
                    <td>
                        <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
                            <?= $this->Html->link($data['User']['username'], array(
                                'controller' => 'users',
                                'action' => 'profile',
                                $data['User']['username']
                            )) ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $this->Admin->time($data['Forum']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Forum']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['forums']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Forum']['id'],
                                                $data['Forum']['title'],
                                                'forum'
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
                                            <?= $this->Admin->delete_perm(
                                                $data['Forum']['id'],
                                                $data['Forum']['title'],
                                                'forum'
                                            ) ?>
                                        </li> 
                                    <?php endif ?>    
                                <?php endif ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        <?php endforeach ?>
    </table>
<?php endif ?>

<?= $this->element('admin_pagination') ?>