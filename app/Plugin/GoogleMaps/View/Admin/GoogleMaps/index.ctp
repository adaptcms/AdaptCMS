<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins',
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Google Maps', null) ?>

<div class="pull-left">
    <h1>Google Maps<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p class="col-lg-7 no-pad-l">With this Plugin you can create a variety of maps and then get the code to display them anywhere you want on your website.</p>
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
	<?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_add'])): ?>
		<?= $this->Html->link('Add Map <i class="fa fa-plus"></i>', array('action' => 'add'), array(
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
	            <th><?= $this->Paginator->sort('map_type', 'Map Type') ?></th>
	            <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
	            <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	            <th></th>
	        </tr>
	        </thead>
	        <tbody>
	        <?php foreach ($this->request->data as $data): ?>
	            <tr>
	                <td>
	                    <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_edit'], $data['User']['id']) && empty($this->request->named['trash'])): ?>
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
	                <td class="hidden-xs">
	                    <?= $this->Admin->time($data['GoogleMap']['created']) ?>
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
	                                    <?= $this->Html->link('<i class="fa fa-code"></i> Get Code',
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
	                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_delete'], $data['User']['id'])): ?>
	                                    <li>
	                                        <?= $this->Admin->remove(
	                                            $data['GoogleMap']['id'],
	                                            $data['GoogleMap']['title']
	                                        ) ?>
	                                    </li>
	                                <?php endif ?>
	                            <?php else: ?>
	                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_restore'], $data['User']['id'])): ?>
	                                    <li>
	                                        <?= $this->Admin->restore(
	                                            $data['GoogleMap']['id'],
	                                            $data['GoogleMap']['title']
	                                        ) ?>
	                                    </li>
	                                <?php endif ?>
	                                <?php if ($this->Admin->hasPermission($permissions['related']['google_maps']['admin_delete'], $data['User']['id'])): ?>
	                                    <li>
	                                        <?= $this->Admin->remove(
	                                            $data['GoogleMap']['id'],
	                                            $data['GoogleMap']['title'],
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

<?php if (!empty($this->request->data)): ?>
    <?php foreach ($this->request->data as $data): ?>
        <div id="code-modal-<?= $data['GoogleMap']['id'] ?>" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
		                <h4 class="modal-title">Map Code</h4>
		            </div>
		            <div class="modal-body">
		                <p>In order to display a map, you have to first place it in a template or a page. Below you will find the code to insert in the place of your choice:</p>

	                    <?= $this->Form->input('code', array(
	                        'type' => 'textarea',
	                        'class' => 'code form-control',
	                        'value' => '{{ partial("GoogleMaps.Maps/' . $data['GoogleMap']['slug'] . '") }}'
	                    )) ?>

		                <p class="clearfix">
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
			</div>
        </div>
    <?php endforeach ?>
<?php endif ?>