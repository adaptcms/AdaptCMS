<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Plugin Permissions', null) ?>

<?= $this->Html->link(
    '<i class="fa fa-list"></i> Back to Plugins',
    array('action' => 'index'),
    array('class' => 'btn btn-info pull-right admin-edit-options', 'escape' => false)
) ?>
<div class="clearfix"></div>

<?= $this->Form->create('Permission', array('class' => 'well')) ?>

    <h2><?= $plugin ?> Permissions</h2>

    <?php if (!empty($roles)): ?>
		<?php $key = 0 ?>
        <?php foreach($roles as $role): ?>
            <div class="span10 no-marg-left">
                <h3><?= $role['Role']['title'] ?></h3>

                <?php if (!empty($role['Permission'])): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>
                                </td>
                                <td>Access</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($role['Permission'] as $row): ?>
                                <tr>
                                    <td>
                                        <?= Inflector::humanize($row['controller']) ?> -> <?= Inflector::humanize($row['action']) ?>
                                    </td>
                                    <td>
                                        <?= $this->Form->hidden($key . '.Permission.id', array('value' => $row['id'])) ?>
                                        <?= $this->Form->input($key . '.Permission.status', array(
                                            'type' => 'checkbox', 
                                            'label' => false,
                                            'default' => $row['status'],
                                            'value' => 1
                                        )) ?>
                                    </td>
                                    <td>
                                        <?php if ($row['own'] != 2): ?>
                                            <?= $this->Form->input($key . '.Permission.own', array(
                                                'type' => 'checkbox', 
                                                'default' => $row['own'],
                                                'value' => 1,
                                                'class' => 'own'
                                            )) ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php if ($row['any'] != 2): ?>
                                            <?= $this->Form->input($key . '.Permission.any', array(
                                                'type' => 'checkbox', 
                                                'default' => $row['any'],
                                                'value' => 1,
                                                'class' => 'any'
                                            )) ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
	                            <?php $key++ ?>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    No Permissions Available
                <?php endif ?>
            </div>
            <div class="clearfix"></div>
        <?php endforeach ?>
    <?php endif ?>
	
<?= $this->Form->end(array(
        'label' => 'Submit',
        'class' => 'btn btn-primary'
)) ?>