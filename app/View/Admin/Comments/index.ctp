<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Comments', null) ?>

<div class="pull-left">
	<h1>Comments<?php if (!empty($this->request->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group" style="float:right;margin-bottom:10px">
	<a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
		View <i class="fa fa-picture-o"></i>
		<span class="caret"></span>
	</a>
	<ul class="dropdown-menu view">
		<li>
			<?=
			$this->Html->link('<i class="fa fa-check"></i> Active', array(
				'admin' => true,
				'action' => 'index'
			), array('escape' => false)) ?>
		</li>
		<li>
			<?=
			$this->Html->link('<i class="fa fa-trash-o"></i> Trash', array(
				'admin' => true,
				'action' => 'index',
				'trash' => 1
			), array('escape' => false)) ?>
		</li>
	</ul>
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
				<th><?= $this->Paginator->sort('Article.title', 'Article') ?></th>
				<th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
				<th class="hidden-xs"><?= $this->Paginator->sort('active') ?></th>
				<th class="hidden-xs"><?= $this->Paginator->sort('created') ?></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($this->request->data as $data): ?>
				<tr>
					<td>
						<?php if ($this->Admin->hasPermission($permissions['related']['articles']['admin_edit'], $data['Article']['id'])): ?>
							<?=
							$this->Html->link($data['Article']['title'], array(
								'controller' => 'articles',
								'action' => 'admin_edit',
								$data['Article']['id']
							)) ?>
						<?php else: ?>
							<?= $data['Article']['title'] ?>
						<?php endif ?>
					</td>
					<td>
						<?php if (empty($data['User']['id'])): ?>
							Guest
						<?php else: ?>
							<?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'], $data['User']['id'])): ?>
								<?=
								$this->Html->link($data['User']['username'], array(
									'controller' => 'users',
									'action' => 'profile',
									$data['User']['username']
								)) ?>
							<?php else: ?>
								<?= $data['User']['username'] ?>
							<?php endif ?>
						<?php endif ?>
					</td>
					<td class="hidden-xs">
						<?php if ($data['Comment']['active'] == 1): ?>
							Yes
						<?php else: ?>
							No
						<?php endif ?>
					</td>
					<td class="hidden-xs">
						<?= $this->Admin->time($data['Comment']['created']) ?>
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
										<?=
										$this->Html->link('<i class="fa fa-picture-o"></i> View', array(
											'admin' => false,
											'controller' => 'articles',
											'action' => 'view',
											'id' => $data['Article']['id'],
											'slug' => $data['Article']['slug'],
											'#' => 'comment_' . $data['Comment']['id']
										), array('escape' => false, 'target' => '_blank')) ?>
									</li>

									<?php if ($this->Admin->hasPermission($permissions['related']['comments']['admin_edit'], $data['User']['id'])): ?>
										<li>
											<?=
											$this->Admin->edit(
												$data['Comment']['id']
											) ?>
										</li>
									<?php endif ?>
									<?php if ($this->Admin->hasPermission($permissions['related']['comments']['admin_delete'], $data['User']['id'])): ?>
										<li>
											<?=
											$this->Admin->remove(
												$data['Comment']['id'],
												''
											) ?>
										</li>
									<?php endif ?>
								<?php else: ?>
									<?php if ($this->Admin->hasPermission($permissions['related']['comments']['admin_edit'], $data['User']['id'])): ?>
										<li>
											<?=
											$this->Admin->restore(
												$data['Comment']['id'],
												''
											) ?>
										</li>
									<?php endif ?>
									<?php if ($this->Admin->hasPermission($permissions['related']['comments']['admin_delete'], $data['User']['id'])): ?>
										<li>
											<?=
											$this->Admin->remove(
												$data['Comment']['id'],
												'',
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