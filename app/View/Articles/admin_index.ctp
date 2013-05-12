<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Articles', null) ?>

<div class="pull-left">
    <h1>
        Articles
        <?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?>
    </h1>
</div>

<div class="btn-toolbar pull-right" style="margin-bottom:10px">
    <?php if ($this->Admin->hasPermission($permissions['related']['articles']['admin_add'])): ?>
        <div class="btn-group">
            <a class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                Add Article
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php foreach ($categories as $category_id => $category): ?>
                    <li>
                        <?= $this->Html->link($category, array(
                            'action' => 'add', 
                            $category_id
                        )) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif ?>
    <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown">
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
    <div class="btn-group">
      <a class="btn dropdown-toggle" data-toggle="dropdown">
        View <i class="icon-picture"></i>
        <span class="caret"></span>
      </a>
      <ul class="dropdown-menu view">
        <li>
            <?= $this->Html->link('<i class="icon-ok"></i> Active', array(
                'admin' => true, 
                'action' => 'index'
            ), array('escape' => false)) ?>
        </li>
        <li>
            <?= $this->Html->link('<i class="icon-trash"></i> Trash', array(
                'admin' => true, 
                'action' => 'index', 
                'trash' => 1
            ), array('escape' => false)) ?>
        </li>
      </ul>
    </div>
</div>
<div class="clearfix"></div>

<?php if (!empty($this->request->data)): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <?= $this->Paginator->sort('title') ?>
                </th>
        		<th>
                    <?= $this->Paginator->sort('User.username', 'Author') ?>
                </th>
        		<th>
                    <?= $this->Paginator->sort('Category.title', 'Category') ?>
                </th>
                <th>
                    <?= $this->Paginator->sort('created') ?>
                </th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($this->request->data as $data): ?>
                <tr>
                    <td>
                       <?php if ($this->Admin->hasPermission($permissions['related']['articles']['view'], $data['User']['id'])): ?>
                            <?= $this->Html->link($data['Article']['title'], array(
                                'admin' => false, 
                                'action' => 'view', 
                                'slug' => $data['Article']['slug'],
                                'id' => $data['Article']['id']
                            )) ?>
                        <?php else: ?>
                            <?= $data['Article']['title'] ?>
                        <?php endif ?>

                        <span class="pull-right">
                            <?php if ($data['Comment']['count'] > 0): ?> 
                                <?= $this->Html->link('<i class="icon icon-comment" title="# of Comments"></i> '.$data['Comment']['count'], array(
                                    'action' => 'edit', 
                                    $data['Article']['id'], 
                                    'comments'
                                    ), array('escape' => false)
                                ) ?>
                            <?php else: ?>
                                <i class="icon icon-comment" title="# of Comments"></i> 
                                <?= $data['Comment']['count'] ?>
                            <?php endif ?>
                        </span>
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
                    <td><?= $data['Category']['title'] ?></td>
                    <td>
                        <?= $this->Admin->time(
                            $data['Article']['created']
                        ) ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                Actions
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($this->Admin->hasPermission($permissions['related']['articles']['view'], $data['User']['id'])): ?>
                                    <li>
                                        <?= $this->Admin->view(
                                            null,
                                            null,
                                            null,
                                            array(
                                                'slug' => $data['Article']['slug'],
                                                'id' => $data['Article']['id']
                                            )
                                        ) ?>
                                    </li>
                                <?php endif ?>

                                <?php if (empty($this->params->named['trash'])): ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['articles']['admin_edit'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->edit(
                                                $data['Article']['id']
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['articles']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete(
                                                $data['Article']['id'],
                                                $data['Article']['title'],
                                                'article'
                                            ) ?>
                                        </li>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['articles']['admin_restore'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->restore(
                                                $data['Article']['id'],
                                                $data['Article']['title']
                                            ) ?>
                                        </li>  
                                    <?php endif ?>
                                    <?php if ($this->Admin->hasPermission($permissions['related']['articles']['admin_delete'], $data['User']['id'])): ?>
                                        <li>
                                            <?= $this->Admin->delete_perm(
                                                $data['Article']['id'],
                                                $data['Article']['title'],
                                                'article'
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
<?php else: ?>
    <div class="well span12 no-marg-left">
        No Articles Found
    </div>
<?php endif ?>

<?= $this->element('admin_pagination') ?>