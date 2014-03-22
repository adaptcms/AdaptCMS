<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Fields', null) ?>

<div class="pull-left">
    <h1>Fields<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <div class="btn-group">
        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            Filter by Category
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($categories as $category_id => $category): ?>
                <li>
                    <?= $this->Html->link($category, array(
                        'category_id' => $category_id
                    )) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="btn-group hidden-xs">
        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            Filter by Module
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($modules as $id => $module): ?>
                <li>
                    <?= $this->Html->link($module, array(
                        'module_id' => $id
                    )) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="btn-group hidden-xs">
        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            Filter by Type
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($field_types as $key => $type): ?>
                <li>
                    <?= $this->Html->link($type, array(
                        'field_type' => $key
                    )) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="btn-group">
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
	    <?php if ($this->Admin->hasPermission($permissions['related']['fields']['admin_add'])): ?>
		    <?= $this->Html->link('Add Field <i class="fa fa-plus"></i>', array('action' => 'add'), array(
			    'class' => 'btn btn-info pull-right',
			    'style' => 'margin-bottom:10px',
			    'escape' => false
		    )) ?>
	    <?php endif ?>
    </div>
</div>
<div class="clearfix"></div>

<?php if (empty($this->request->data)): ?>
    <div class="well">
        No Items Found
    </div>
<?php else: ?>
	<div class="table-responsive">
	    <table class="table table-striped">
	        <thead>
	            <tr>
	                <th><?= $this->Paginator->sort('title') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('FieldType.title', 'Type') ?></th>
	                <th><?= $this->Paginator->sort('Category.title', 'Category') ?></th>
	                <th><?= $this->Paginator->sort('Module.title', 'Module') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('User.username', 'Author') ?></th>
	                <th class="hidden-xs">
	                    <?php if (!empty($this->request->named['trash'])): ?>
	                        <?= $this->Paginator->sort('deleted_time', 'Deleted') ?>
	                    <?php else: ?>
	                        <?= $this->Paginator->sort('created') ?>
	                    <?php endif ?>
	                </th>
	                <th></th>
	            </tr>
	        </thead>

	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td>
	                        <?php if ($this->Admin->hasPermission($permissions['related']['fields']['admin_edit'], $data['User']['id'])): ?>
	                            <?= $this->Html->link($data['Field']['title'], array(
	                                'action' => 'edit',
	                                $data['Field']['id']
	                            )) ?>
	                        <?php else: ?>
	                            <?= $data['Field']['title'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?= $data['FieldType']['label'] ?>
	                    </td>
	                    <td>
	                        <?php if (!empty($data['Category']['id'])): ?>
	                            <?= $this->Html->link($data['Category']['title'], array(
	                                    'controller' => 'categories',
	                                    'action' => 'admin_edit',
	                                    $data['Category']['id']
	                            )) ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <?php if (!empty($data['Module']['id'])): ?>
	                            <?= $data['Module']['title'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
	                            <?= $this->Html->link($data['User']['username'], array(
	                                'controller' => 'users',
	                                'action' => 'profile',
	                                $data['User']['username']
	                            )) ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?php if (!empty($this->request->named['trash'])): ?>
	                            <?= $this->Admin->time($data['Field']['deleted_time']) ?>
	                        <?php else: ?>
	                            <?= $this->Admin->time($data['Field']['created']) ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <?php if (empty($this->request->named['trash'])): ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['fields']['admin_edit'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Field']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['fields']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Field']['id'],
	                                                $data['Field']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php else: ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['fields']['admin_restore'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->restore(
	                                                $data['Field']['id'],
	                                                $data['Field']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['fields']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Field']['id'],
	                                                $data['Field']['title'],
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