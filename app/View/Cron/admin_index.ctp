<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('controller' => 'tools', 'action' => 'index')) ?>
<?php $this->Html->addCrumb('Cron', null) ?>

<div class="pull-left">
    <h1>Cron Entries<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p class="span8">This area is for advanced users. With the Cron Job functionality, users can setup functionality to run on a specific schedule. One example is to set the database to be optimized once every week or a new sitemap to be pinged/generated once a day.</p>
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
<div class="clear"></div>

<?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_add'])): ?>
    <?= $this->Html->link('Add Cron Job <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
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
                <th><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>

        <?php foreach ($this->request->data as $data): ?>
            <tbody>
                <tr>
                    <td>
                        <?= $this->Html->link($data['Cron']['title'], array(
                            'action' => 'view', 
                            $data['Cron']['id']
                        )) ?>
                    </td>
                    <td>
                        <?= $this->Admin->time($data['Cron']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Cron']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Cron']['id'],
                                                $data['Cron']['title'],
                                                'cron item'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['Cron']['id'],
                                                $data['Cron']['title']
                                            ) ?>
                                        </li>  
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['Cron']['id'],
                                                $data['Cron']['title'],
                                                'cron item'
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