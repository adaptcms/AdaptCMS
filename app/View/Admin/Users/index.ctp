<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Users', null) ?>

<?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_ajax_change_user'])): ?>
    <div id="user-change-status"></div>
<?php endif ?>

<div class="left">
    <h1>Users<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>

<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <div class="btn-group">
        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            Filter by Role
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($roles as $role_id => $role): ?>
                <li>
                    <?= $this->Html->link($role, array(
                        'role_id' => $role_id
                    )) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            Filter by Status
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <?= $this->Html->link('Active', array(
                    'status' => 1
                )) ?>
            </li>
            <li>
                <?= $this->Html->link('Not Activated', array(
                    'status' => 0
                )) ?>
            </li>
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
    </div>
	<div class="btn-group">
		<?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_add'])): ?>
			<?= $this->Html->link('Add User <i class="fa fa-plus"></i>', array('action' => 'add'), array(
				'class' => 'btn btn-info ',
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
	                <th><?= $this->Paginator->sort('username') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('status') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('email', 'E-Mail') ?></th>
	                <th><?= $this->Paginator->sort('Role.title', 'Role') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td>
	                        <?php if (empty($this->request->named['trash']) && $this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
	                            <?= $this->Html->link(
	                                    $data['User']['username'], array(
	                                        'admin' => false,
	                                        'action' => 'profile',
	                                        $data['User']['username']
	                                    )
	                            ) ?>
	                        <?php else: ?>
	                            <?= $data['User']['username'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs" style="text-align: center">
	                        <?php if ($data['User']['status'] == 0): ?>
	                            <i class="fa fa-times-circle user-status" data-id="<?= $data['User']['id'] ?>" title="Click to activate User" alt="Click to activate User"></i>
	                        <?php else: ?>
		                        <i class="fa fa-check-circle user-status" data-id="<?= $data['User']['id'] ?>" title="Click to de-activate User" alt="Click to de-activate User"></i>
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?= $data['User']['email'] ?>
	                    </td>
	                    <td>
	                        <?= $data['Role']['title'] ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?= $this->Admin->time($data['User']['created']) ?>
	                    </td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <?php if (empty($this->request->named['trash'])): ?>
		                                <?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
			                                <li>
				                                <?= $this->Admin->view(
					                                $data['User']['username'],
					                                'profile'
				                                ) ?>
			                                </li>
		                                <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_edit'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['User']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['User']['id'],
	                                                $data['User']['username']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php else: ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_restore'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->restore(
	                                                $data['User']['id'],
	                                                $data['User']['username']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['users']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['User']['id'],
	                                                $data['User']['username'],
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