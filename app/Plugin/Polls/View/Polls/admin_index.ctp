<h1>Polls</h1>
<?php echo $this->Html->link('Add Poll', array('action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>
<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('title') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?= $data['Poll']['title'] ?>
        </td>
        <td><?php echo $this->Time->format('F jS, Y h:i A', $data['Poll']['created']); ?></td>
        <td>
            <?php echo $this->Html->link(
                '<i class="icon-pencil icon-white"></i> Edit', 
                array('action' => 'edit', $data['Poll']['id']),
                array('class' => 'btn btn-primary', 'escape' => false));
            ?>
            <?php echo $this->Html->link(
                '<i class="icon-trash icon-white"></i> Delete',
                array('action' => 'delete', $data['Poll']['id'], $data['Poll']['title']),
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