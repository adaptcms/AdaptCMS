<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Categories', null) ?>

<div class="pull-left">
    <h1>Categories<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group" style="float:right;margin-bottom:10px">
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
<div class="clear"></div>

<?php if ($this->Admin->hasPermission($permissions['related']['categories']['admin_add'])): ?>
    <?= $this->Html->link('Add Category <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
        'class' => 'btn btn-info pull-right', 
        'style' => 'margin-bottom:10px',
        'escape' => false
    )) ?>
<?php endif ?>

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
                <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->request->data as $data): ?>
                <tr>
                    <td>
                        <?php if ($this->Admin->hasPermission($permissions['related']['categories']['view'], $data['User']['id'])): ?>
                            <?= $this->Html->link($data['Category']['title'], array(
                                'admin' => false,
                                'action' => 'view',
                                $data['Category']['slug']
                            )) ?>
                        <?php else: ?>
                            <?= $data['Category']['title'] ?>
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
                    <td class="hidden-phone">
                        <?= $this->Admin->time($data['Category']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (empty($this->request->named['trash'])): ?>
	                                <?php if ($this->Admin->hasPermission($permissions['related']['categories']['view'], $data['User']['id'])): ?>
		                                <li>
			                                <?= $this->Admin->view(
				                                $data['Category']['slug']
			                                ) ?>
		                                </li>
	                                <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['categories']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Category']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['categories']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Category']['id'],
                                                $data['Category']['title'],
                                                'category'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['categories']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['Category']['id'],
                                                $data['Category']['title']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['categories']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['Category']['id'],
                                                $data['Category']['title'],
                                                'category'
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
<?php endif ?>

<?= $this->element('admin_pagination') ?>