<h3>Permissions</h3>

<table class="table" id="permissions">
	<thead>
	<th>Role</th>
	<th>Has Access?</th>
	</thead>
	<tbody>
	<?php foreach($roles as $key => $role): ?>
		<tr>
			<td><?= $role['name'] ?></td>
			<td>
				<?= $this->Form->input('Article.settings.permissions.' . $role['id'] . '.view.status', array(
					'type' => 'checkbox',
					'label' => false,
					'div' => false,
					'checked' => $role['status']
				)) ?>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>