<script>
$(document).ready(function() {
	$("#databaseForm").validate();
});
</script>

<h2>Database Configuration</h2>

<?= $this->Form->create('', array('class' => 'well')) ?>
	<?= $this->Form->input('host', array(
		'class' => 'required',
		'label' => 'Database Host',
		'value' => 'localhost'
	)) ?>
	<?= $this->Form->input('login', array(
		'class' => 'required',
		'label' => 'Database Username'
	)) ?>
	<?= $this->Form->input('password', array(
		'class' => 'required',
		'label' => 'Database Password'
	)) ?>
	<?= $this->Form->input('database', array(
		'class' => 'required',
		'label' => 'Database Name'
	)) ?>
	<?= $this->Form->input('prefix', array(
		'label' => 'Database Table Prefix'
	)) ?>
<?= $this->Form->end('Continue') ?>