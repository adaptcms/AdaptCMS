<h1>Roles</h1>
<?php echo $this->Html->link('Add Role', array('controller' => 'Roles', 'action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>
<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('title') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?php echo $this->Html->link($data['Role']['title'], array('admin' => false, 'controller' => 'Roles', 'action' => 'view', $data['Role']['id'])); ?>
        </td>
        <td><?php echo $this->Time->format('F jS, Y h:i A', $data['Role']['created']); ?></td>
        <td>
            <?php echo $this->Html->link(
                '<i class="icon-pencil icon-white"></i> Edit', 
                array('action' => 'edit', $data['Role']['id']),
                array('class' => 'btn btn-primary', 'escape' => false));
            ?>
            <?php echo $this->Html->link(
                '<i class="icon-trash icon-white"></i> Delete',
                array('action' => 'delete', $data['Role']['id'], $data['Role']['title']),
                array('class' => 'btn btn-danger', 'escape' => false));
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->element('admin_pagination') ?>