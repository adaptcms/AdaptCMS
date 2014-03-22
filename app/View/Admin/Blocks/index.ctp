<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Blocks', null) ?>

<div class="pull-left col-lg-7 no-marg-left">
    <h1>Blocks<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p>Blocks are the meat of displaying data on any area of your site. At the start, features such as Articles, Categories and plugins such as AdaptBB, Links and Polls utilize this to show a poll or list the newest articles.</p>
</div>
<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <div class="btn-group">
        <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            Filter by Type
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach ($block_types as $key => $type): ?>
                <li>
                    <?= $this->Html->link($type, array(
                        'type' => $key
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
	    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_add'])): ?>
		    <?= $this->Html->link('Add Block <i class="fa fa-plus"></i>', array('action' => 'add'), array(
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
	                <th><?= $this->Paginator->sort('type') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('User.username', 'Author') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td>
	                        <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_edit'], $data['User']['id'])): ?>
	                            <?= $this->Html->link($data['Block']['title'], array(
	                                'action' => 'edit',
	                                $data['Block']['id']
	                            )) ?>
	                        <?php else: ?>
	                            <?= $data['Block']['title'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <?= $block_types[$data['Block']['type']] ?>
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
	                        <?= $this->Admin->time($data['Block']['created']) ?>
	                    </td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <?php if (empty($this->request->named['trash'])): ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_edit'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Block']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Block']['id'],
	                                                $data['Block']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php else: ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_restore'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->restore(
	                                                $data['Block']['id'],
	                                                $data['Block']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['blocks']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Block']['id'],
	                                                $data['Block']['title'],
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