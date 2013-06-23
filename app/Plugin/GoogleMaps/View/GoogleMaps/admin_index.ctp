<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins',
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Google Maps', null) ?>

<div class="pull-left">
    <h1>Google Maps<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p class="span7">With this Plugin you can create a variety of maps and then get the code to display them anywhere you want on your website.</p>
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

<?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_add'])): ?>
    <?= $this->Html->link('Add Map <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
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
            <th><?= $this->Paginator->sort('map_type', 'Map Type') ?></th>
            <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
            <th class="hidden-phone"><?= $this->Paginator->sort('created') ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->request->data as $data): ?>
            <tr>
                <td>
                    <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_edit'], $data['User']['id'])): ?>
                        <?= $this->Html->link($data['GoogleMap']['title'], array(
                            'action' => 'admin_edit',
                            $data['GoogleMap']['id']
                        )) ?>
                    <?php else: ?>
                        <?= $data['GoogleMap']['title'] ?>
                    <?php endif ?>
                </td>
                <td>
                    <?= $map_types[$data['GoogleMap']['map_type']] ?>
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
                    <?= $this->Admin->time($data['GoogleMap']['created']) ?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (empty($this->params->named['trash'])): ?>
                                <li>
                                    <?= $this->Html->link('<i class="icon-code"></i> Get Code',
                                        array(
                                            '#' => 'code-modal-' . $data['GoogleMap']['id']
                                        ), array(
                                            'escape' => false,
                                            'data-toggle' => 'modal'
                                        )) ?>
                                </li>
                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_edit'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Admin->edit(
                                            $data['GoogleMap']['id']
                                        ) ?>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_edit'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Admin->delete(
                                            $data['GoogleMap']['id'],
                                            $data['GoogleMap']['title'],
                                            'map'
                                        ) ?>
                                    </li>
                                <?php endif ?>
                            <?php else: ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_edit'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Admin->restore(
                                            $data['GoogleMap']['id'],
                                            $data['GoogleMap']['title']
                                        ) ?>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_edit'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Admin->delete_perm(
                                            $data['GoogleMap']['id'],
                                            $data['GoogleMap']['title'],
                                            'map'
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

<?php if (!empty($this->request->data)): ?>
    <?php foreach ($this->request->data as $data): ?>
        <div id="code-modal-<?= $data['GoogleMap']['id'] ?>" class="modal hide fade" tabindex="-1" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Map Code</h3>
            </div>
            <div class="modal-body">
                <p>In order to display a map, you have to first place it in a template or a page. Below you will find the code to insert in the place of your choice:</p>

                <p>
                    <?= $this->Form->input('code', array(
                        'type' => 'textarea',
                        'class' => 'code span5',
                        'value' => '<?= $this->Element("GoogleMaps.Maps/' . $data['GoogleMap']['slug'] . '") ?>'
                    )) ?>
                </p>

                <p>
                    As for what template or page to insert this in, it is up to you. But below we will list some possible places for putting it in.
                    We recommend 'Layouts Default' when using the default theme or the Page 'home':
                </p>

                <ul>
                    <?php foreach($pages as $page): ?>
                        <li>
                            <?= $this->Html->link('Page - ' . $page['Page']['title'], array(
                                'plugin' => null,
                                'controller' => 'pages',
                                'action' => 'edit',
                                $page['Page']['id']
                            )) ?>
                        </li>
                    <?php endforeach ?>
                </ul>

                <ul>
                    <?php foreach($templates as $template): ?>
                        <li>
                            <?= $this->Html->link($template['Template']['label'], array(
                                'plugin' => null,
                                'controller' => 'templates',
                                'action' => 'edit',
                                $template['Template']['id']
                            )) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    <?php endforeach ?>
<?php endif ?>