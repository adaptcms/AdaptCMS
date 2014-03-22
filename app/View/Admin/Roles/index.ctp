<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Roles', null) ?>

<div class="pull-left">
    <h1>Roles<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
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
	<?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_add'])): ?>
		<?= $this->Html->link('Add Role <i class="fa fa-plus"></i>', array('action' => 'add'), array(
			'class' => 'btn btn-info pull-right',
			'style' => 'margin-bottom:10px',
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
	<div class="table-responsive">
		<table class="table table-striped">
		    <thead>
		        <tr>
		            <th><?= $this->Paginator->sort('title') ?></th>
		            <th><?= $this->Paginator->sort('created') ?></th>
		            <th></th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php foreach ($this->request->data as $data): ?>
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
		                            <?php if (empty($this->request->named['trash'])): ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_edit'])): ?>
		                                    <li>
		                                        <?= $this->Admin->edit(
		                                            $data['Role']['id']
		                                        ) ?>
		                                    </li>
		                                <?php endif ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['roles']['admin_delete'])): ?>
		                                    <li>
		                                        <?= $this->Admin->remove(
		                                            $data['Role']['id'],
		                                            $data['Role']['title']
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
		                                        <?= $this->Admin->remove(
		                                            $data['Role']['id'],
		                                            $data['Role']['title'],
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