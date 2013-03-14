<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Settings', null) ?>

<div class="pull-left">
    <h1>Setting Categories<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
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
<div class="clearfix"></div>

<?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_add'])): ?>
    <?= $this->Html->link('Add Settings Category <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
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
                        <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_edit'], $data['Setting']['id'])): ?>
                            <?= $this->Html->link($data['Setting']['title'], array(
                            'action' => 'edit', $data['Setting']['id'])) ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $this->Admin->time($data['Setting']['created']) ?>
                    </td>
            		<td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_edit'], $data['Setting']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Setting']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_delete'], $data['Setting']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Setting']['id'],
                                                $data['Setting']['title'],
                                                'setting category'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_restore'], $data['Setting']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['Setting']['id'],
                                                $data['Setting']['title']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_delete'], $data['Setting']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['Setting']['id'],
                                                $data['Setting']['title'],
                                                'setting category'
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