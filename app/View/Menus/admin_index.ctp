<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Menu', null) ?>

<div class="pull-left span7 no-marg-left">
    <h1>Menus<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p>The menu manager allows you to create as many menus as you would like. Add custom links or link to current static pages, articles, whatever you'd like.</p>
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

<?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_add'])): ?>
    <?= $this->Html->link('Add Menu <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
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
                        <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_edit'], $data['User']['id'])): ?>
                            <?= $this->Html->link($data['Menu']['title'], array(
                                'action' => 'admin_edit',
                                $data['Menu']['id']
                            )) ?>
                        <?php else: ?>
                            <?= $data['Menu']['title'] ?>
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
                        <?= $this->Admin->time($data['Menu']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (empty($this->request->named['trash'])): ?>
                                    <li>
                                        <?= $this->Html->link('<i class="icon-code"></i> Get Code',
                                        array(
                                            '#' => 'code-modal'
                                        ), array(
                                            'escape' => false,
                                            'data-toggle' => 'modal',
                                            'data-slug' => $data['Menu']['slug'],
                                            'id' => 'menu-' . $data['Menu']['id']
                                        )) ?>
                                    </li>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Menu']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->remove(
                                                $data['Menu']['id'],
                                                $data['Menu']['title']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_restore'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['Menu']['id'],
                                                $data['Menu']['title']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->remove(
                                                $data['Menu']['id'],
                                                $data['Menu']['title'],
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
<?php endif ?>

<?= $this->element('admin_pagination') ?>

<div id="code-modal" class="modal hide fade" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>Menu Code</h3>
    </div>
    <div class="modal-body">
        <p>In order to display a menu, you have to first place it in a template. The typical place is the layout template so it is always shown. Below you will find the code to insert in the place of your choice:</p>

        <p>
            <?= $this->Form->input('code', array(
                'type' => 'textarea',
                'class' => 'code span5',
                'value' => ''
            )) ?>
        </p>

        <p>
            As for what template to insert this in, it is up to you. But below we will list some possible places for putting it in. We recommend 'Layouts Default' when using the default theme:
        </p>

        <ul>
            <?php foreach($templates as $template): ?>
                <li>
                    <?= $this->Html->link($template['Template']['label'], array(
                        'controller' => 'templates',
                        'action' => 'edit',
                        $template['Template']['id']
                    )) ?>
                </li>
            <?php endforeach ?>
        </ul>

        <div class="hidden element"><?= '?= $this->Element("Menus/[slug]") ?' ?></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-info" data-dismiss="modal">Close</button>
    </div>
</div>