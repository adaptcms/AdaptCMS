<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Settings', null) ?>

<div class="pull-left">
    <h1>Settings<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group pull-right">
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
	<?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_add'])): ?>
		<?= $this->Html->link('Add Settings Category <i class="fa fa-plus"></i>', array('action' => 'add'), array(
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
	                                <?php if (empty($this->request->named['trash'])): ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_edit'], $data['Setting']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Setting']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['settings']['admin_delete'], $data['Setting']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Setting']['id'],
	                                                $data['Setting']['title']
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
	                                            <?= $this->Admin->remove(
	                                                $data['Setting']['id'],
	                                                $data['Setting']['title'],
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