<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', null) ?>

<div class="pull-left">
    <h1>Files<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
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

<?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_add'])): ?>
    <div class="btn-group pull-right" style="margin-bottom:10px">
        <?= $this->Html->link('Upload Multiple <i class="icon icon-list icon-white"></i>', array(
                'action' => 'add', 
                'multiple' => true
            ), array(
                'class' => 'btn btn-info', 
                'escape' => false
        )) ?>
        <?= $this->Html->link('Add File <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
            'class' => 'btn btn-info', 
            'escape' => false
        )) ?>
    </div>
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
                <th><?= $this->Paginator->sort('filename', 'File Name') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('mimetype', 'Type') ?></th>
                <th class="hidden-phone">Preview</th>
                <th class="hidden-phone"><?= $this->Paginator->sort('filesize', 'Size') ?></th>
                <th class="hidden-phone"><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->request->data as $data): ?>
                <tr>
                    <td>
                        <?php if ($this->Admin->hasPermission($permissions['related']['files']['view'], $data['User']['id'])): ?>
                            <?= $this->Html->link(
                                $data['File']['filename'],
                                '/' . $data['File']['dir'] . $data['File']['filename'],
                                array('target' => '_blank')
                            ) ?>
                        <?php else: ?>
                            <?= $data['File']['filename'] ?>
                        <?php endif ?>
                    </td>
                    <td class="hidden-phone"><?= $data['File']['mimetype'] ?></td>
                    <td class="hidden-phone" style="text-align: center">
                        <?php if (strstr($data['File']['mimetype'], "image")):?>
                            <?= $this->Html->image("/".$data['File']['dir']."thumb/".$data['File']['filename']) ?>
                        <?php endif; ?>
                    </td>
                    <td class="hidden-phone"><?= $this->Number->toReadableSize($data['File']['filesize']) ?></td>
                    <td class="hidden-phone">
                        <?= $this->Admin->time($data['File']['created']) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($this->Admin->hasPermission($permissions['related']['files']['view'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Html->link(
                                            '<i class="icon-picture"></i> View',
                                            '/' . $data['File']['dir'] . $data['File']['filename'],
                                            array('target' => '_blank', 'escape' => false)
                                        ) ?>
                                    </li>
                                <?php endif ?>

                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['File']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['File']['id'],
                                                null,
                                                'file'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_restore'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['File']['id'],
                                                null
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['File']['id'],
                                                null,
                                                'file'
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