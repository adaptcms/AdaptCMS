<h1 class="pull-left">
    Settings Categories
</h1>
<?= $this->Html->link('Add Settings Category <i class="icon icon-plus icon-white"></i>', array('action' => 'add'), array(
    'class' => 'btn btn-info pull-right', 
    'style' => 'margin-bottom:10px',
    'escape' => false
)) ?>
<div class="clearfix"></div>

<?php if (empty($this->request->data)): ?>
    <div class="well">
        No Items Found
    </div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th></th>
            </tr>
        </thead>

        <?php foreach ($this->request->data as $data): ?>
            <tbody>
                <tr>
                    <td>
                        <?= $this->Html->link($data['Setting']['title'], array(
                            'action' => 'edit', $data['Setting']['id'])); ?>
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
                                <?php if (empty($this->params->named['trash'])): ?>
                                    <li>
                                        <?= $this->Admin->edit(
                                            $data['Setting']['id']
                                        ) ?>
                                    </li>
                                    <li>
                                        <?= $this->Admin->delete(
                                            $data['Setting']['id'],
                                            $data['Setting']['title'],
                                            'setting category'
                                        ) ?>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <?= $this->Admin->restore(
                                            $data['Setting']['id'],
                                            $data['Setting']['title']
                                        ) ?>
                                    </li>  
                                    <li>
                                        <?= $this->Admin->delete_perm(
                                            $data['Setting']['id'],
                                            $data['Setting']['title'],
                                            'setting category'
                                        ) ?>
                                    </li>     
                                <?php endif ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        <?php endforeach ?>
    </table>
<?php endif ?>

<?= $this->element('admin_pagination') ?>