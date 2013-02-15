<script>
$(document).ready(function(){
	$("#CategoryAdminForm").validate();
});
</script>

<?= $this->Form->create('Category', array('class' => 'well', 'id' => 'CategoryAdminForm')) ?>
	<h2>Add Category</h2>

	<?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>