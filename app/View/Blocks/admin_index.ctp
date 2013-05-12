<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Blocks', null) ?>

<div class="pull-left span7 no-marg-left">
    <h1>Blocks<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p>Blocks are the meat of displaying data on any area of your site. At the start, features such as Articles, Categories and plugins such as AdaptBB, Links and Polls utilize this to show a poll or list the newest articles.</p>
</div>
<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown">
            Filter by Block Type
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($block_types as $key => $type): ?>
                <li>
                    <?= $this->Html->link($type, array(
                        'type' => $key
                    )) ?>
                </li>
            <?php endforeach; ?>
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

<?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_add'])): ?>
    <?= $this->Html->link('Add Block <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
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
                <th><?= $this->Paginator->sort('type') ?></th>
                <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>

        <?php foreach ($this->request->data as $data): ?>
            <tbody>
                <tr>
                    <td>
                        <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_edit'], $data['User']['id'])): ?>
                            <?= $this->Html->link($data['Block']['title'], array(
                                'action' => 'edit', 
                                $data['Block']['id']
                            )) ?>
                        <?php else: ?>
                            <?= $data['Block']['title'] ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $block_types[$data['Block']['type']] ?>
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
                        <?= $this->Admin->time($data['Block']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Block']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Block']['id'],
                                                $data['Block']['title'],
                                                'block'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_restore'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['Block']['id'],
                                                $data['Block']['title']
                                            ) ?>
                                        </li>  
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['Block']['id'],
                                                $data['Block']['title'],
                                                'block'
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