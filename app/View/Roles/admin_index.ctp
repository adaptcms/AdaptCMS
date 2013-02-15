<div class="pull-left">
    <h1>Roles<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
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

<?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_add'])): ?>
    <?= $this->Html->link('Add Role <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
        'class' => 'btn btn-info pull-right', 
        'style' => 'margin-bottom:10px',
        'escape' => false
    )) ?>
<?php endif ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('title') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th></th>
        </tr>
    </thead>

    <?php foreach ($this->request->data as $data): ?>
        <tbody>
            <tr>
                <td>
                    <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_edit'])): ?>
                        <?= $this->Html->link($data['Role']['title'], array(
                            'action' => 'edit', 
                            $data['Role']['id']
                        )) ?>
                    <?php else: ?>
                        <?= $data['Role']['title'] ?>
                    <?php endif ?>
                </td>
                <td>
                    <?= $this->Admin->time($data['Role']['created']) ?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (empty($this->params->named['trash'])): ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_edit'])): ?>
                                    <li>
                                        <?= $this->Admin->edit(
                                            $data['Role']['id']
                                        ) ?>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_delete'])): ?>
                                    <li>
                                        <?= $this->Admin->delete(
                                            $data['Role']['id'],
                                            $data['Role']['title'],
                                            'role'
                                        ) ?>
                                    </li>
                                <?php endif ?>
                            <?php else: ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_restore'])): ?>
                                    <li>
                                        <?= $this->Admin->restore(
                                            $data['Role']['id'],
                                            $data['Role']['title']
                                        ) ?>
                                    </li>  
                                <?php endif ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_delete'])): ?>
                                    <li>
                                        <?= $this->Admin->delete_perm(
                                            $data['Role']['id'],
                                            $data['Role']['title'],
                                            'role'
                                        ) ?>
                                    </li> 
                                <?php endif ?>
                            <?php endif ?>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    <?php endforeach; ?>
</table>

<?= $this->element('admin_pagination') ?>