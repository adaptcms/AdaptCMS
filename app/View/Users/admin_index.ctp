<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Users', null) ?>

<?php if ($this->Admin->hasPermission($permissions['related']['users']['ajax_change_user'])): ?>
    <div id="user-change-status"></div>
<?php endif ?>

<div class="left">
    <h1>Users<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>

<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown">
            Filter by Role
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($roles as $role_id => $role): ?>
                <li>
                    <?= $this->Html->link($role, array(
                        'role_id' => $role_id
                    )) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown">
            Filter by Status
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <?= $this->Html->link('Active', array(
                    'status' => 1
                )) ?>
            </li>
            <li>
                <?= $this->Html->link('Not Activated', array(
                    'status' => 0
                )) ?>
            </li>
        </ul>
    </div>
    <div class="btn-group">
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
</div>
<div class="clearfix"></div>

<?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_add'])): ?>
    <?= $this->Html->link('Add User <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
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
                <th><?= $this->Paginator->sort('username') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('status') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('email', 'E-Mail') ?></th>
                <th><?= $this->Paginator->sort('Role.title', 'Role') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->request->data as $data): ?>
                <tr>
                    <td>
                        <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
                            <?= $this->Html->link(
                                    $data['User']['username'], array(
                                        'admin' => false,
                                        'action' => 'profile',
                                        $data['User']['username']
                                    )
                            ) ?>
                        <?php else: ?>
                            <?= $data['User']['username'] ?>
                        <?php endif ?>
                    </td>
                    <td class="hidden-phone" style="text-align: center">
                        <?php if ($data['User']['status'] == 0): ?>
                            <i class="icon-remove-sign user-status" data-id="<?= $data['User']['id'] ?>" title="Click to activate User" alt="Click to activate User"></i>
                        <?php else: ?>
                            <i class="icon-ok-sign user-status"></i>
                        <?php endif ?>
                    </td>
                    <td class="hidden-phone">
                        <?= $data['User']['email'] ?>
                    </td>
                    <td>
                        <?= $data['Role']['title'] ?>
                    </td>
                    <td class="hidden-phone">
                        <?= $this->Admin->time($data['User']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Admin->view(
                                            $data['User']['username'],
                                            'profile'
                                        ) ?>
                                    </li>
                                <?php endif ?>

                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['User']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['User']['id'],
                                                $data['User']['username'],
                                                'user'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_restore'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['User']['id'],
                                                $data['User']['username']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['User']['id'],
                                                $data['User']['username'],
                                                'user'
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