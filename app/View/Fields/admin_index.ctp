<div class="left">
    <h1>Fields<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
</div>
<div class="btn-group" style="float:right;margin-bottom:10px">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    View
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu" style="min-width: 0px">
    <li><?= $this->Html->link('Active', array('admin' => true, 'action' => 'index')) ?></li>
    <li><?= $this->Html->link('Trash', array('admin' => true, 'action' => 'index', 'trash' => 1)) ?></li>
  </ul>
</div>
<div class="clear"></div>

<?php echo $this->Html->link('Add Field', array('action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>
<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('title') ?></th>
        <th><?= $this->Paginator->sort('field_type', 'Type') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?= $data['Field']['title'] ?>
        </td>
        <td>
            <?= ucfirst($data['Field']['field_type']) ?>
        </td>
        <td><?php echo $this->Time->format('F jS, Y h:i A', $data['Field']['created']); ?></td>
        <td>
            <?php if (empty($this->params->named['trash'])): ?>
                <?= $this->Html->link(
                    '<i class="icon-pencil icon-white"></i> Edit', 
                    array('action' => 'edit', $data['Field']['id']),
                    array('class' => 'btn btn-primary', 'escape' => false));
                ?>
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete',
                    array('action' => 'delete', $data['Field']['id'], $data['Field']['title']),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this field?')"));
                ?>
            <?php else: ?>
                <?= $this->Html->link(
                    '<i class="icon-share-alt icon-white"></i> Restore', 
                    array('action' => 'restore', $data['Field']['id'], $data['Field']['title']),
                    array('class' => 'btn btn-success', 'escape' => false));
                ?>    
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete Forever',
                    array('action' => 'delete', $data['Field']['id'], $data['Field']['title'], 1),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this field? This is permanent.')"));
                ?>      
            <?php endif ?>
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