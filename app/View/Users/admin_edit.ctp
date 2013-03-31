<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Users', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit User', null) ?>

<?php
	$this->TinyMce->editor();
?>

<?= $this->Html->css("data-tagging.css") ?>
<?= $this->Html->script('data-tagging.js') ?>

<ul id="admin-tab" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Edit User</a>
	</li>
	<?php if (!empty($this->request->data['Article'])): ?>
		<li>
			<a href="#articles" data-toggle="tab">Articles by User</a>
		</li>
	<?php endif ?>
	<li>
		<a href="#settings" data-toggle="tab">User Settings</a>
	</li>
</ul>

<div class="right">
    <?= $this->Html->link(
        '<i class="icon-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="icon-trash icon-white"></i> Delete',
        array('action' => 'delete', $this->request->data['User']['id'], $this->request->data['User']['username']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this user?')"));
    ?>
</div>
<div class="clearfix"></div>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane active fade in" id="main">
		<?= $this->Form->create('User', array('type' => 'file', 'class' => 'well admin-validate')) ?>
			<h2>Edit User</h2>
		
		<?php    
			echo $this->Form->input('username', array('type' => 'text', 'class' => 'required'));
			echo $this->Form->input('password', array('type' => 'password', 'label' => 'New Password?', 'required' => false));
			echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Confirm New Password'));
			echo $this->Form->input('email', array('type' => 'text', 'class' => 'required'));
			echo $this->Form->input('role_id', array('empty' => '- choose -', 'class' => 'required'));

			echo $this->Form->hidden('modified', array('value' => $this->Time->format('Y-m-d H:i:s', time())));
		    echo $this->Form->hidden('id');
		 ?>

		<?php if (!empty($this->request->data['SecurityQuestions']['SettingValue']['data'])): ?>
		    <?php if (!empty($security_options)): ?>
		        <?= $this->Form->input('security_question_hidden', array(
		                'options' => $security_options,
		                'label' => false,
		                'div' => false,
		                'style' => 'display:none'
		        )) ?>
		        <?php for($i = 1; $i <= $this->request->data['SecurityQuestions']['SettingValue']['data']; $i++): ?>
		            <?= $this->Form->input('Security.'.$i.'.question', array(
		                    'empty' => '- choose -', 
		                    'class' => 'required security-question', 
		                    'options' => $security_options,
		                    'label' => 'Security Question '.$i
		            )) ?>
		            <div id="Security<?= $i ?>Question" style="display: none">
		                <?= $this->Form->input('Security.'.$i.'.answer', array(
		                        'class' => 'required',
		                        'label' => 'Security Answer '.$i
		                )) ?>
		            </div>
		        <?php endfor ?>
		    <?php endif ?>
		<?php endif ?>

		<?= $this->Form->input('theme_id', array(
		    'label' => 'Theme', 
		    'empty' => '- Choose Theme -'
		)) ?>

		<?= $this->Form->input('User.settings.time_zone', array(
		    'label' => 'Timezone',
		    'empty' => '- Choose -',
		    'options' => $timezones
		)) ?>

		<?php if (!empty($this->request->data['User']['settings']['avatar'])): ?>
		    <h4>Current Avatar</h4>

		    <?= $this->Html->image(
		        '/uploads/avatars/' . $this->request->data['User']['settings']['avatar'],
		        array('class' => 'thumbnail span2')
		    ) ?>
		    <?= $this->Form->hidden('User.settings.old_avatar', array(
		        'value' => $this->request->data['User']['settings']['avatar']
		    )) ?>
		    <div class="clearfix"></div>
		<?php endif ?>

		<?= $this->Form->input('User.settings.avatar', array(
		    'label' => 'Avatar',
		    'type' => 'file'
		)) ?>

		<?= $this->Form->input('status', array(
		    'options' => array(
		        'In-Active',
		        'Active'
		    )
		)) ?>

		<br />
		<?= $this->Form->end(array(
				'label' => 'Submit',
				'class' => 'btn'
		)) ?>
	</div>

	<?php if (!empty($this->request->data['Article'])): ?>
		<div class="tab-pane" id="articles">
			<table class="table">
			    <tr>
			        <th>Title</th>
			        <th>Category</th>
			        <th>Status</th>
			        <th>Created</th>
			    </tr>
				<?php foreach($this->request->data['Article'] as $article): ?>
					<tr>
						<td>
							<?= $this->Html->link($article['title'], array(
									'controller' => 'articles', 
									'action' => 'edit', 
									$article['id']
							), array('target' => '_blank')) ?>
						</td>
						<td>
							<?= $this->Html->link($article['Category']['title'], array(
									'controller' => 'categories',
									'action' => 'edit', 
									$article['Category']['id']
							), array('target' => '_blank')) ?>
						</td>
						<td>
							<?php if ($article['status'] == 1): ?>
								Article is Live
							<?php elseif ($article['status'] == 0 && $article['publish_time'] == "0000-00-00 00:00:00"): ?>
								Article is Saved as a Draft, NOT live
							<?php else: ?>
								Article will go Live - 
								<?= $this->Time->format("F d, Y h:i a", $article['publish_time']) ?>
							<?php endif ?>
						</td>
						<td>
							<?= $this->Time->format('F jS, Y h:i A', $article['created']) ?>
						</td>
					</tr>
				<?php endforeach ?>
			</table>
		</div>
	<?php endif ?>

	<?php if (!empty($settings)): ?>
		<div class="tab-pane" id="settings">
			<?= $this->Form->create('SettingValue', array(
					'controller' => 'settings', 
					'action' => 'edit/'.$settings[0]['Setting']['id'].'/'.$this->request->data['User']['id'], 
					'class' => 'well admin-validate'
			)) ?>

				<h2>User Settings</h2>

				<?php foreach($settings as $row): ?>
					<?= $this->Form->input('SettingValue.'.$row['SettingValue']['id'].'.title', array('value' => $row['SettingValue']['title'], 'class' => 'required')) ?>
					<?php if ($row['SettingValue']['setting_type'] == "textarea"): ?>
							<?= $this->Form->input('SettingValue.'.$row['SettingValue']['id'].'.data', array('value' => $row['SettingValue']['data'], 'style' => 'width:500px', 'rows' => 15)) ?>
					<?php elseif ($row['SettingValue']['setting_type'] == "text"): ?>
						<?= $this->Form->input('SettingValue.'.$row['SettingValue']['id'].'.data', array('type' => 'text', 'value' => $row['SettingValue']['data'])) ?>
					<?php elseif ($row['SettingValue']['setting_type'] == "dropdown"): ?>
						<?php 
							$data_options = null;
							foreach(json_decode($row['SettingValue']['data_options']) as $json) {
								$data_options[$json] = $json;
							}
						?>
						<?= $this->Form->input('SettingValue.'.$row['SettingValue']['id'].'.data', array(
							'value' => $row['SettingValue']['data'], 
							'options' => $data_options,
							'empty' => '- Choose -'
					)) ?>
					<?php endif; ?>
					<?= $this->Form->input('SettingValue.'.$row['SettingValue']['id'].'.description', array(
						'value' => $row['SettingValue']['description'], 
						'rows' => 15, 
						'style' => 'width:500px',
						'class' => 'required'
					)) ?><br />

					<?= $this->Form->hidden('SettingValue.'.$row['SettingValue']['id'].'.id', array('value' => $row['SettingValue']['id'])) ?>
					<?= $this->Form->hidden('SettingValue.'.$row['SettingValue']['id'].'.modified', array(
							'value' => $this->Time->format('Y-m-d H:i:s', time())
					)) ?>
			 	<?php endforeach; ?>

			<br />
			<?= $this->Form->end(array(
					'label' => 'Submit',
					'class' => 'btn btn-primary'
			)) ?>
		</div>
	<?php endif ?>
</div>