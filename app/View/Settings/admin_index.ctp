<h1>Settings Categories</h1>
<?= $this->Html->link('Add Settings Category', array('controller' => 'Settings', 'action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>
<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('title') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?= $this->Html->link($data['Setting']['title'], array(
                'action' => 'edit', $data['Setting']['id'])); ?>
        </td>
        <td><?= $this->Time->format('F jS, Y h:i A', $data['Setting']['created']); ?></td>
		 <td>
            <?= $this->Html->link(
                '<i class="icon-pencil icon-white"></i> Edit', array(
                    'action' => 'edit', 
                    $data['Setting']['id']
                    ),
                array(
                    'class' => 'btn btn-primary', 
                    'escape' => false
                )) ?>
            <?= $this->Html->link(
                '<i class="icon-trash icon-white"></i> Delete',
                array('action' => 'delete', $data['Setting']['id'], $data['Setting']['title']),
                array('class' => 'btn btn-danger', 'escape' => false));
            ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
    $numbers = $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentClass' => 'active paginator', 'first' => '1'));
?>

<?php if (!empty($numbers)): ?>
    <div class="pagination">
        <ul>
            <?= $this->Paginator->prev('«', array('tag' => 'li'), '<li><a>«</a></li>', array('escape' => false)) ?>
            <?= $numbers ?>
            <?= $this->Paginator->next('»', array('tag' => 'li'), '<li><a>«</a></li>', array('escape' => false)) ?>
        </ul>
    </div>
<?php endif ?>