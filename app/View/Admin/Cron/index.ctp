<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('controller' => 'tools', 'action' => 'index')) ?>
<?php $this->Html->addCrumb('Cron Jobs', null) ?>

<div class="pull-left col-lg-7 no-marg-left">
    <h1>Cron Jobs<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p>
        This area is for advanced users. With the Cron Job functionality, users can setup functionality to run on a specific schedule.
        One example is to set the database to be optimized once every week or a new sitemap to be pinged/generated once a day.
    </p>
    <p>
        <strong>Notice</strong> When a cron job does not run succesfully, it's active flag is set to inactive. You can try to 'Run Test' and if it runs through withour errors,
        it will automatically re-activate itself. Otherwise, let us/the plugin manufacturer know of the issue.
    </p>
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
	<?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_add'])): ?>
		<?= $this->Html->link('Add Cron Job <i class="fa fa-plus"></i>', array('action' => 'add'), array(
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
	                <th>Runs Every...</th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('last_run', 'Last Run') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('run_time', 'Next Run') ?></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td>
	                        <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_edit'])): ?>
	                            <?= $this->Html->link($data['Cron']['title'], array(
	                                'action' => 'edit',
	                                $data['Cron']['id']
	                            )) ?>
	                        <?php else: ?>
	                            <?= $data['Cron']['title'] ?>
	                        <?php endif ?>
	                    </td>
	                    <td>
	                        <?php if (empty($data['Cron']['active'])): ?>
	                            <span class="label label-info">Not Active</span>
	                        <?php else: ?>
	                            <?= $data['Cron']['period_amount'] ?>
	                            <?= $data['Cron']['period_type'] ?>(s)
	                        <?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
                            <?php if (!empty($data['Cron']['last_run'])): ?>
		                        <?= $this->Admin->time($data['Cron']['last_run']) ?>
							<?php endif ?>
	                    </td>
	                    <td class="hidden-xs">
	                        <?= $this->Admin->time($data['Cron']['run_time']) ?>
	                    </td>
	                    <td>
	                        <div class="btn-group">
	                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
	                                Actions
	                                <span class="caret"></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <?php if (empty($this->request->named['trash'])): ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_edit'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Cron']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_delete'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Cron']['id'],
	                                                $data['Cron']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_test'])): ?>
	                                        <li>
	                                            <?= $this->Html->link('<i class="fa fa-cog"></i> Run Test', array(
	                                                'action' => 'test',
	                                                $data['Cron']['id']
	                                            ), array('escape' => false)) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php else: ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_restore'])): ?>
	                                        <li>
	                                            <?= $this->Admin->restore(
	                                                $data['Cron']['id'],
	                                                $data['Cron']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['cron']['admin_delete'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Cron']['id'],
	                                                $data['Cron']['title'],
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