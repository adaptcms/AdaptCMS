<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Menu', null) ?>

<div class="pull-left col-lg-7 no-pad-l">
    <h1>Menus<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
    <p>The menu manager allows you to create as many menus as you would like. Add custom links or link to current static pages, articles, whatever you'd like.</p>
</div>
<div class="btn-toolbar pull-right">
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
		<?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_add'])): ?>
			<?= $this->Html->link('Add Menu <i class="fa fa-plus"></i>', array('action' => 'add'), array(
				'class' => 'btn btn-info pull-right',
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
	                <th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
	                <th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php foreach ($this->request->data as $data): ?>
	                <tr>
	                    <td>
	                        <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_edit'], $data['User']['id']) && $this->Admin->isActive($data, 'Menu')): ?>
	                            <?= $this->Html->link($data['Menu']['title'], array(
	                                'action' => 'admin_edit',
	                                $data['Menu']['id']
	                            )) ?>
	                        <?php else: ?>
	                            <?= $data['Menu']['title'] ?>
	                        <?php endif ?>
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
	                        <?= $this->Admin->time($data['Menu']['created']) ?>
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
	                                            '#' => 'code-modal'
	                                        ), array(
	                                            'escape' => false,
	                                            'data-toggle' => 'modal',
	                                            'data-slug' => $data['Menu']['slug'],
	                                            'id' => 'menu-' . $data['Menu']['id']
	                                        )) ?>
	                                    </li>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_edit'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->edit(
	                                                $data['Menu']['id']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Menu']['id'],
	                                                $data['Menu']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                <?php else: ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_restore'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->restore(
	                                                $data['Menu']['id'],
	                                                $data['Menu']['title']
	                                            ) ?>
	                                        </li>
	                                    <?php endif ?>
	                                    <?php if ($this->Admin->hasPermission($permissions['related']['menus']['admin_delete'], $data['User']['id'])): ?>
	                                        <li>
	                                            <?= $this->Admin->remove(
	                                                $data['Menu']['id'],
	                                                $data['Menu']['title'],
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

<div id="code-modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
		        <h4 class="modal-title">Menu Code</h4>
		    </div>
		    <div class="modal-body">
		        <p>In order to display a menu, you have to first place it in a template. The typical place is the layout template so it is always shown. Below you will find the code to insert in the place of your choice:</p>

	            <?= $this->Form->input('code', array(
	                'type' => 'textarea',
	                'class' => 'code form-control',
	                'value' => ''
	            )) ?>

		        <p class="clearfix">
		            As for what template to insert this in, it is up to you. But below we will list some possible places for putting it in. We recommend 'Layouts Default' when using the default theme:
		        </p>

		        <ul>
		            <?php foreach($templates as $template): ?>
		                <li>
		                    <?= $this->Html->link($template['Template']['label'], array(
		                        'controller' => 'templates',
		                        'action' => 'edit',
		                        $template['Template']['id']
		                    )) ?>
		                </li>
		            <?php endforeach ?>
		        </ul>

		        <div class="hidden element"><?= '{{ partial("Menus/[slug]") }}' ?></div>
		    </div>
		    <div class="modal-footer">
		        <button class="btn btn-info" data-dismiss="modal">Close</button>
		    </div>
		</div>
	</div>
</div>