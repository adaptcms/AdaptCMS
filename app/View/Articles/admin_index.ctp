<?php 
    // $this->LastFM->key = '452d325ac8204b59da9d71468994b6b8';
    // $this->LastFM->album_getInfo(array('artist' => 'The Beatles', 'album' => 'Revolver'));
?>

<div class="left">
    <h1>Articles<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
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

<div class="btn-group" style="float:right;margin-bottom:10px">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    Add Article
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <?php foreach ($categories as $category_id => $cat): ?>
    <li><?= $this->Html->link($cat, array('action' => 'add', $category_id)); ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('title') ?></th>
		<th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
		<th><?= $this->Paginator->sort('Category.title', 'Category') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?= $this->Html->link($data['Article']['title'], array('admin' => false, 'controller' => 'Articles', 'action' => 'view', $data['Article']['slug'])); ?>
        </td>
        <td><?= $data['User']['username'] ?></td>
        <td><?= $data['Category']['title'] ?></td>
        <td><?= $this->Time->format('F jS, Y h:i A', $data['Article']['created']); ?></td>
        <td>
            <?php if (empty($this->params->named['trash'])): ?>
                <?= $this->Html->link(
                    '<i class="icon-pencil icon-white"></i> Edit', 
                    array('action' => 'edit', $data['Article']['id']),
                    array('class' => 'btn btn-primary', 'escape' => false));
                ?>
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete',
                    array('action' => 'delete', $data['Article']['id'], $data['Article']['title']),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this article?')"));
                ?>
            <?php else: ?>
                <?= $this->Html->link(
                    '<i class="icon-share-alt icon-white"></i> Restore', 
                    array('action' => 'restore', $data['Article']['id'], $data['Article']['title']),
                    array('class' => 'btn btn-success', 'escape' => false));
                ?>    
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete Forever',
                    array('action' => 'delete', $data['Article']['id'], $data['Article']['title'], 1),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this article? This is permanent.')"));
                ?>      
            <?php endif ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->element('admin_pagination') ?>