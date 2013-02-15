<?php
	$this->CodeMirror->editor('PageContent');
?>

<script>
 $(document).ready(function(){
    $("#PageEditForm").validate();
 });
 </script>

<h2 class="left">Edit Page</h2>

<div class="right admin-edit-options">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['Page']['id'], $this->request->data['Page']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this page?')"));
    ?>
</div>
<div class="clearfix"></div>

<?php
    echo $this->Form->create('Page', array('class' => 'well'));
	echo $this->Form->input('title', array('type' => 'text', 'class' => 'required'));
	echo $this->Form->hidden('old_title', array('value' => $this->request->data['Page']['title']));
	echo $this->Form->input('content', array('style' => 'width:80%;height: 300px', 'class' => 'required'));

	echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
    echo $this->Form->hidden('id');
 ?>

<br />
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>