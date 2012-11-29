<div class="left">
    <h1>Files<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
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

<script>
$(".btn-danger").live('click', function() {
    $("#FileDeleteForm").submit();
});
</script>

<?php echo $this->Html->link('Add New File', array('action' => 'add'), array('class' => 'btn', 'style' => 'float:right;margin-bottom:10px')); ?>
<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('filename', 'File Name') ?></th>
        <th><?= $this->Paginator->sort('mimetype', 'Type') ?></th>
        <th>Preview</th>
        <th><?= $this->Paginator->sort('filesize', 'Size') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?php echo $this->Html->link($data['File']['filename'], $this->params->webroot.$data['File']['dir'].$data['File']['filename'], array('target' => '_new')); ?>
        </td>
        <td><?= $data['File']['mimetype'] ?></td>
        <td style="text-align: center">
            <?php if (strstr($data['File']['mimetype'], "image")):?>
                <?= $this->Html->image("/".$data['File']['dir']."thumb/".$data['File']['filename']) ?>
            <?php endif; ?>
        </td>
        <td><?= $this->Number->toReadableSize($data['File']['filesize']) ?></td>
        <td><?php echo $this->Time->format('F jS, Y h:i A', $data['File']['created']); ?></td>
        <td>
            <?php if (empty($this->params->named['trash'])): ?>
                <?= $this->Html->link(
                    '<i class="icon-pencil icon-white"></i> Edit', 
                    array('action' => 'edit', $data['File']['id']),
                    array('class' => 'btn btn-primary', 'escape' => false));
                ?>
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete',
                    array('action' => 'delete', $data['File']['id']),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file?')"));
                ?>
            <?php else: ?>
                <?= $this->Html->link(
                    '<i class="icon-share-alt icon-white"></i> Restore', 
                    array('action' => 'restore', $data['File']['id'], $data['File']['filename']),
                    array('class' => 'btn btn-success', 'escape' => false));
                ?>    
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete Forever',
                    array('action' => 'delete', $data['File']['id'], 1),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this file? This is permanent.')"));
                ?>      
            <?php endif ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->element('admin_pagination') ?>