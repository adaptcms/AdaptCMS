<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Files', null) ?>

<div class="pull-left">
    <h1>Files<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group" style="float:right;margin-bottom:10px">
  <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
    View <i class="fa fa-picture-o"></i>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu view">
    <li>
        <?= $this->Html->link('<i class="fa fa-check"></i> Active', array(
            'admin' => true,
            'action' => 'index'
        ), array('escape' => false)) ?>
    </li>
    <li>
        <?= $this->Html->link('<i class="fa fa-trash-o"></i> Trash', array(
            'admin' => true,
            'action' => 'index',
            'trash' => 1
        ), array('escape' => false)) ?>
    </li>
  </ul>
</div>
<div class="clearfix"></div>

<div class="btn-group pull-right" style="margin-bottom:10px">
	<?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_add_folder'])): ?>
		<?= $this->Html->link('Import Folder <i class="fa fa-folder-open"></i>', array(
			'action' => 'add_folder'
		), array(
			'class' => 'btn btn-info',
			'escape' => false
		)) ?>
	<?php endif ?>
	<?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_add'])): ?>
	        <?= $this->Html->link('Upload Multiple <i class="fa fa-list"></i>', array(
	                'action' => 'add',
	                'multiple' => true
	            ), array(
	                'class' => 'btn btn-info',
	                'escape' => false
	        )) ?>
	        <?= $this->Html->link('Add File <i class="fa fa-plus"></i>', array('action' => 'add'), array(
	            'class' => 'btn btn-info',
	            'escape' => false
	        )) ?>
	<?php endif ?>
</div>
<div class="clearfix"></div>

<?php if (empty($this->request->data)): ?>
    <div class="well">
        No Items Found
    </div>
<?php else: ?>
	<div class="table-responsive" style="margin-right: 10px;">
	    <table class="table table-striped">
	        <thead>
	            <tr>
	                <th><?= $this->Paginator->sort('filename', 'File Name') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('mimetype', 'Type') ?></th>
	                <th class="hidden-xs">Preview</th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('filesize', 'Size') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td class="col-lg-2">
	                        <?php if ($this->Admin->hasPermission($permissions['related']['files']['view'], $data['User']['id'])): ?>
	                            <?= $this->Html->link(
	                                $data['File']['label'],
	                                '/' . $data['File']['dir'] . $data['File']['filename'],
	                                array('target' => '_blank')
	                            ) ?>
	                        <?php else: ?>
	                            <?= $data['File']['label'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs"><?= $data['File']['mimetype'] ?></td>
	                    <td class="hidden-xs" style="text-align: center">
	                        <?php if (strstr($data['File']['mimetype'], "image")):?>
	                            <?= $this->Html->image("/".$data['File']['dir']."thumb/".$data['File']['filename'], array('class' => 'col-lg-10')) ?>
	                        <?php endif; ?>
	                    </td>
	                    <td class="hidden-xs"><?= $this->Number->toReadableSize($data['File']['filesize']) ?></td>
	                    <td class="hidden-xs">
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
	                                            '<i class="fa fa-picture-o"></i> View',
	                                            '/' . $data['File']['dir'] . $data['File']['filename'],
	                                            array('target' => '_blank', 'escape' => false)
	                                        ) ?>
	                                    </li>
	                                <?php endif ?>

	                                <?php if (empty($this->request->named['trash'])): ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_edit'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['File']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['files']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['File']['id'],
		                                            null
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
	                                            <?= $this->Admin->remove(
	                                                $data['File']['id'],
	                                                null,
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
	</div>
<?php endif ?>

<?= $this->element('admin_pagination') ?>