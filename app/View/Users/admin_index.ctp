<script type="text/javascript">
$(document).ready(function(){

    $(".user-status").live('click', function() {
        if ($(this).hasClass('icon-remove-sign')) {
            var user_id = $(this).attr('id');
            var new_status = 1;

            $.post("<?= $this->webroot ?>ajax/users/change_user/", {data:{User:{id:user_id, status: new_status}}}, function(data) {
                if ($("#user-change-status").length != 0) {
                    $("#user-change-status").replaceWith(data);
                } else {
                    $(data).insertBefore($(".span9 h1"));
                }

                if ($("#user-change-status").hasClass('alert-success')) {
                    $("#" + user_id).removeClass('icon-remove-sign').addClass('icon-ok-sign');
                }
            });
        }
    });

});
</script>

<div id="user-change-status"></div>

<div class="left">
    <h1>Users<?php if (!empty($this->params->named['trash'])): ?> - Trash<?php endif ?></h1>
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

<?= $this->Html->link('Add User', array(
        'action' => 'add'
    ), 
    array(
        'class' => 'btn', 
        'style' => 'float:right;margin-bottom:10px'
    )
) ?>

<table class="table table-bordered">
    <tr>
        <th><?= $this->Paginator->sort('username') ?></th>
        <th><?= $this->Paginator->sort('status') ?></th>
        <th><?= $this->Paginator->sort('email', 'E-Mail') ?></th>
        <th><?= $this->Paginator->sort('Role.title', 'Role') ?></th>
        <th><?= $this->Paginator->sort('created') ?></th>
        <th>Options</th>
    </tr>

    <?php foreach ($this->request->data as $data): ?>
    <tr>
        <td>
            <?= $this->Html->link(
                    $data['User']['username'], array(
                        'admin' => false, 
                        'controller' => 'Users', 
                        'action' => 'view', 
                        $data['User']['id']
                    )
            ) ?>
        </td>
        <td style="text-align: center">
            <?php if ($data['User']['status'] == 0): ?>
                <i class="icon-remove-sign user-status" id="<?= $data['User']['id'] ?>"></i>
            <?php else: ?>
                <i class="icon-ok-sign user-status"></i>
            <?php endif ?>
        </td>
        <td>
            <?= $data['User']['email'] ?>
        </td>
        <td>
            <?= $data['Role']['title'] ?>
        </td>
        <td>
            <?= $this->Time->format(
                    'F jS, Y h:i A', 
                    $data['User']['created']
            ) ?>
        </td>
        <td>
            <?php if (empty($this->params->named['trash'])): ?>
                <?= $this->Html->link(
                    '<i class="icon-pencil icon-white"></i> Edit', 
                    array('action' => 'edit', $data['User']['id']),
                    array('class' => 'btn btn-primary', 'escape' => false));
                ?>
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete',
                    array('action' => 'delete', $data['User']['id'], $data['User']['username']),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this user?')"));
                ?>
            <?php else: ?>
                <?= $this->Html->link(
                    '<i class="icon-share-alt icon-white"></i> Restore', 
                    array('action' => 'restore', $data['User']['id'], $data['User']['username']),
                    array('class' => 'btn btn-success', 'escape' => false));
                ?>    
                <?= $this->Html->link(
                    '<i class="icon-trash icon-white"></i> Delete Forever',
                    array('action' => 'delete', $data['User']['id'], $data['User']['username'], 1),
                    array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this user? This is permanent.')"));
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