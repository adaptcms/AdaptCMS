<?php
/**
 * Class Plugin
 */
class Plugin extends AppModel
{
    public $name = 'Plugin';
    public $useTable = false;

    public function getActivePath()
    {
        return APP . 'Plugin' . DS;
    }

    public function getInactivePath()
    {
        return APP . 'Old_Plugins' . DS;
    }

	/**
	 * Convienence method
	 * Goes through folder of Plugins, setting all data needed on plugin listing.
	 *
	 * @param string $path of specified Plugin folder
	 * @return array plugin data with api information
	 */
	public function getPlugins($path)
	{
		$exclude = array(
			'DebugKit',
			'empty'
		);
		$plugins = array();
		$api_lookup = array();

		if ($dh = opendir($path))
		{
			while (($file = readdir($dh)) !== false)
			{
				if (!in_array($file, $exclude) && $file != ".." && $file != ".")
				{
					$json = $path . DS . $file . DS . 'plugin.json';

					if ($plugin = $this->getPluginJson($json))
					{
						$plugins[$file] = $plugin;

						if (!empty($plugins[$file]['api_id'])) {
							$api_lookup[] = $plugins[$file]['api_id'];
						}
					} else {
						$plugins[$file]['title'] = $file;
					}

					$upgrade = $path . DS . $file . DS . 'Install' . DS . 'upgrade.json';

					if (file_exists($upgrade) && is_readable($upgrade))
					{
						$plugins[$file]['upgrade_status'] = 1;
					} else {
						$plugins[$file]['upgrade_status'] = 0;
					}

					if (strstr($path, 'Old')) {
						$plugins[$file]['status'] = 0;
					} else {
						$plugins[$file]['status'] = 1;
					}

					$config = $path . DS . $file . DS . 'Config' . DS . 'config.php';

					if (file_exists($config) && is_readable($config))
					{
						$params = array();

						require($config);

						$param_check = json_decode($params);

						if (!empty($param_check))
							$plugins[$file]['config'] = 1;
					}

					if (!isset($plugins[$file]['config']))
						$plugins[$file]['config'] = 0;
				}
			}
		}

		return array(
			'plugins' => $plugins,
			'api_lookup' => $api_lookup
		);
	}

	/**
	 * Convienence method, need to get plugin JSON file contents several times in this controller.
	 *
	 * @param string $path
	 * @internal param \of $path plugin JSON file
	 * @return array|boolean of data, false if it can't get file contents
	 */
	public function getPluginJson($path)
	{
		if (file_exists($path) && is_readable($path))
		{
			$handle = fopen($path, "r");
			$file = fread($handle, filesize($path));

			return json_decode($file, true);
		}

		return false;
	}

	/**
	 * Create Plugin
	 *
	 * @param $data
	 *
	 * @return void
	 */
	public function createPlugin($data)
	{
		$name = Inflector::camelize($data['basicInfo']['name']);
		$model = Inflector::singularize($name);
		$controller = Inflector::pluralize($name);

		$path = $this->getInactivePath();
		$defaults = array(
			'Config' => array(
				'config.php' => '<?php
$params = \'[]\';

$config = json_decode($params, true);
Configure::write(\'{name}\', $config );',
				'routes.php' => ''
			),
			'Controller' => array(
				'header' => '<?php
App::uses(\'AppController\', \'Controller\');

/**
* Class {name}
*/
class {name}Controller extends AppController
{
	/**
	 * Name of the Controller, \'{name}\'
	 */
	public $name = \'{name}\';

	/**
	 * array of permissions for this page
	 */
	private $permissions;

	/**
	 * In this beforeFilter we get the permissions
	 */
	public function beforeFilter()
	{
	    parent::beforeFilter();

	    $this->permissions = $this->getPermissions();
	}',
				'with_model' => '

	/**
	 * Returns a paginated index of {model} items
	 *
	 * @return array of block data
	 */
	public function admin_index()
	{
	    $conditions = array();

	    if ($this->permissions[\'any\'] == 0)
	    {
	        $conditions[\'User.id\'] = $this->Auth->user(\'id\');
	    }

	    if (!isset($this->request->named[\'trash\'])) {
	        $conditions[\'{model}.deleted_time\'] = \'0000-00-00 00:00:00\';
	    } else {
	        $conditions[\'{model}.deleted_time !=\'] = \'0000-00-00 00:00:00\';
	    }

	    $this->Paginator->settings = array(
	        \'order\' => \'{model}.created DESC\',
	        \'conditions\' => array(
	            $conditions
	        ),
	        \'contain\' => array(
	            \'User\'
	        )
	    );

	    $this->request->data = $this->Paginator->paginate(\'{model}\');
	}

	/**
	 * Returns nothing before post
	 *
	 * On POST, returns error flash or success flash and redirect to index on success
	 *
	 * @return mixed
	 */
	public function admin_add()
	{
	    if (!empty($this->request->data))
	    {
	        $this->request->data[\'{model}\'][\'user_id\'] = $this->Auth->user(\'id\');

	        if ($this->{model}->save($this->request->data))
	        {
	            $this->Session->setFlash(\'Your {model} has been added.\', \'success\');
	            $this->redirect(array(\'action\' => \'index\'));
	        } else {
	            $this->Session->setFlash(\'Unable to add your {model}.\', \'error\');
	        }
	    }
	}

	/**
	 * Before POST, sets request data to form
	 *
	 * After POST, flash error or flash success and redirect to index
	 *
	 * @param int - ID of the database entry
	 * @return array of {model} data
	 */
	public function admin_edit($id)
	{
	    $this->{model}->id = $id;

	    if (!empty($this->request->data))
	    {
	        $this->request->data[\'{model}\'][\'user_id\'] = $this->Auth->user(\'id\');

	        if ($this->{model}->save($this->request->data))
	        {
	            $this->Session->setFlash(\'Your {model} has been updated.\', \'success\');
	            $this->redirect(array(\'action\' => \'index\'));
	        } else {
	            $this->Session->setFlash(\'Unable to update your {model}.\', \'error\');
	        }
	    }

	    $this->request->data = $this->{model}->read();
	}

	/**
	 * If item has no delete time, then initial deletion is to the trash area (making it in-active on site, if applicable)
	 *
	 * But if it has a deletion time, meaning it is in the trash, deleting it the second time is permanent.
	 *
	 * @param int - ID of the database entry, redirect to index if no permissions
	 * @param string - Title of this entry, used for flash message
	 * @param boolean $permanent
	 * @internal param \If $permanent not NULL, this means the item is in the trash so deletion will now be permanent
	 * @return redirect
	 */
	public function admin_delete($id, $title = null, $permanent = null)
	{
	    $this->{model}->id = $id;

	    if (!empty($permanent))
	    {
	        $delete = $this->{model}->delete($id);
	    } else {
	        $delete = $this->{model}->saveField(\'deleted_time\', $this->{model}->dateTime());
	    }

	    if ($delete)
	    {
	        $this->Session->setFlash(\'The {model} `\'.$title.\'` has been deleted.\', \'success\');
	    } else {
	        $this->Session->setFlash(\'The {model} `\'.$title.\'` has NOT been deleted.\', \'error\');
	    }

	    if (!empty($permanent))
	    {
	        $count = $this->{model}->find(\'count\', array(
	            \'conditions\' => array(
	                \'{model}.deleted_time !=\' => \'0000-00-00 00:00:00\'
	            )
	        ));

	        $params = array(\'action\' => \'index\');

	        if ($count > 0)
	        {
	            $params[\'trash\'] = 1;
	        }

	        $this->redirect($params);
	    } else {
	        $this->redirect(array(\'action\' => \'index\'));
	    }
	}

	/**
	 * Restoring an item will take an item in the trash and reset the delete time
	 *
	 * This makes it live wherever applicable
	 *
	 * @param int - ID of database entry, redirect if no permissions
	 * @param string - Title of this entry, used for flash message
	 * @return redirect
	 */
	public function admin_restore($id, $title = null)
	{
	    $this->{model}->id = $id;

	    if ($this->{model}->saveField(\'deleted_time\', \'0000-00-00 00:00:00\'))
	    {
	        $this->Session->setFlash(\'The {model} `\'.$title.\'` has been restored.\', \'success\');
	        $this->redirect(array(\'action\' => \'index\'));
	    } else {
	        $this->Session->setFlash(\'The {model} `\'.$title.\'` has NOT been restored.\', \'error\');
	        $this->redirect(array(\'action\' => \'index\'));
	    }
	}

	/**
	 * Index Method
	 * Returns a paginated list of sample items
	 *
	 * @return void
	 */
	public function index()
	{
		$conditions = array();

		$conditions[\'{model}.deleted_time\'] = \'0000-00-00 00:00:00\';

		if ($this->permissions[\'any\'] == 0)
		{
			$conditions[\'User.id\'] = $this->Auth->user(\'id\');
		}

		$this->Paginator->settings = array(
			\'order\' => \'{model}.created DESC\',
			\'conditions\' => $conditions,
			\'contain\' => array(
				\'User\'
			)
		);

		$this->request->data = $this->Paginator->paginate(\'{model}\');
	}

	/**
	 * View Method
	 * Returns a data array if it finds the sample by the supplied slug
	 *
	 * @param string $slug
	 *
	 * @return void
	 */
	public function view($slug)
	{
		$this->request->data = $this->{model}->find(\'first\', array(
			\'conditions\' => array(
				\'{model}.slug\' => $slug
			),
			\'contain\' => array(
				\'User\'
			)
		));
	}'
			),
			'Install' => array(
				'permissions.php' => '<?php

class PermissionsInstall
{
    private $guest_data = array(
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'index\',
            \'status\' => 1
        ),
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'view\',
            \'status\' => 1
        )
    );

    private $admin_data = array(
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'admin_index\',
            \'related\' => \'[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]\'
        ),
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'admin_add\'
        ),
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'admin_edit\'
        ),
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'admin_delete\'
        ),
        array(
            \'controller\' => \'{controller}\',
            \'action\' => \'admin_restore\'
        )
    );

    /**
     * Generate
     *
     * @param array $roles
     * @param integer $module_id
     * @return array
     */
    public function generate($roles = array(), $module_id = 0)
    {
        $data = array();

        if (!empty($roles))
        {
            $this->admin_data = array_merge($this->admin_data, $this->guest_data);

            foreach($roles as $role)
            {
                $role_id = $role[\'Role\'][\'id\'];

                if ($role[\'Role\'][\'defaults\'] == \'default-admin\')
                {
                    foreach($this->admin_data as $permission)
                    {
                        $data[][\'Permission\'] = array(
                            \'module_id\' => $module_id,
                            \'role_id\' => $role_id,
                            \'plugin\' => \'{controller}\',
                            \'controller\' => $permission[\'controller\'],
                            \'action\' => $permission[\'action\'],
                            \'status\' => 1,
                            \'related\' => !empty($permission[\'related\']) ? $permission[\'related\'] : \'\',
                            \'own\' => 1,
                            \'any\' => 1
                        );
                    }
                }
                else
                {
                    foreach($this->admin_data as $permission)
                    {
                        $data[][\'Permission\'] = array(
                            \'module_id\' => $module_id,
                            \'role_id\' => $role_id,
                            \'plugin\' => \'{controller}\',
                            \'controller\' => $permission[\'controller\'],
                            \'action\' => $permission[\'action\'],
                            \'status\' => strstr($permission[\'action\'], \'admin\') ? 0 : 1,
                            \'related\' => !empty($permission[\'related\']) ? $permission[\'related\'] : \'\',
                            \'own\' => 0,
                            \'any\' => 0
                        );
                    }
                }
            }
        }

        return $data;
    }
}',
				'install_data.sql' => 'CREATE TABLE IF NOT EXISTS `{prefix}plugin_{model}s` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `text` longtext DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------'
			),
			'Model' => array(
				'appModel' => '<?php
App::uses(\'AppModel\', \'Model\');
class {model}AppModel extends AppModel
{
}',
				'header' => '<?php
App::uses(\'{model}AppModel\', \'{model}.Model\');
/**
 * Class {model}
 */
class {model} extends {model}AppModel
{
	public $name = \'Plugin{model}\';

    /**
     * @var array
     */
    public $belongsTo = array(
        \'User\' => array(
            \'className\' => \'User\',
            \'foreignKey\' => \'user_id\'
        )
    );

    /**
     * Our validation rules, name of map.
     */
    public $validate = array(
        \'title\' => array(
            array(
                \'rule\' => \'notEmpty\',
                \'message\' => \'Please enter in a title\'
            )
        )
    );

    /**
     * @var array
     */
    public $actsAs = array(\'Slug\');',
				'block_active' => '

	/**
	 * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
	 * created the block. This is customizable so you can do a contain of related data if you wish.
	 *
	 * @param $data
	 * @param $user_id
	 * @return array
	 */
	public function getBlockData($data, $user_id)
	{
	    $cond = array(
	        \'conditions\' => array(
	            \'{model}.deleted_time\' => \'0000-00-00 00:00:00\'
	        )
	    );

	    if (!empty($data[\'limit\'])) {
	        $cond[\'limit\'] = $data[\'limit\'];
	    }

	    if (!empty($data[\'order_by\'])) {
	        if ($data[\'order_by\'] == "rand") {
	            $data[\'order_by\'] = \'RAND()\';
	        }

	        $cond[\'order\'] = \'{model}.\' . $data[\'order_by\'] . \' \' . $data[\'order_dir\'];
	    }

	    if (!empty($data[\'data\'])) {
	        $cond[\'conditions\'][\'{model}.id\'] = $data[\'data\'];
	    }

	    return $this->find(\'all\', $cond);
	}'
			),
			'View' => array(
				'Admin' => array(
					'index.ctp' => '<?php $this->Html->addCrumb(\'Admin\', \'/admin\') ?>
<?php $this->Html->addCrumb(\'Plugins\', array(
    \'controller\' => \'plugins\',
    \'action\' => \'index\',
    \'plugin\' => false
)) ?>
<?php $this->Html->addCrumb(\'{full_name}\', null) ?>

<h1>{full_name}<?php if (!empty($this->request->named[\'trash\'])): ?> - Trash<?php endif ?></h1>
<div class="btn-group pull-right">
    <a class="btn dropdown-toggle" data-toggle="dropdown">
        View <i class="icon-picture"></i>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu view">
        <li>
            <?= $this->Html->link(\'<i class="icon-ok"></i> Active\', array(
                \'admin\' => true,
                \'action\' => \'index\'
            ), array(\'escape\' => false)) ?>
        </li>
        <li>
            <?= $this->Html->link(\'<i class="icon-trash"></i> Trash\', array(
                \'admin\' => true,
                \'action\' => \'index\',
                \'trash\' => 1
            ), array(\'escape\' => false)) ?>
        </li>
    </ul>
</div>
<div class="clear"></div>

<?php if ($this->Admin->hasPermission($permissions[\'related\'][\'{model_lower}\'][\'admin_add\'])): ?>
    <?= $this->Html->link(\'Add {model} <i class="icon icon-plus icon-white"></i>\', array(\'action\' => \'add\'), array(
        \'class\' => \'btn btn-info pull-right\',
        \'style\' => \'margin-bottom:10px\',
        \'escape\' => false
    )) ?>
<?php endif ?>

<?php if (empty($this->request->data)): ?>
    <div class="clearfix"></div>
    <div class="well">
        No {full_name} Found
    </div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort(\'title\') ?></th>
            <th><?= $this->Paginator->sort(\'User.username\', \'Author\') ?></th>
            <th class="hidden-phone"><?= $this->Paginator->sort(\'created\') ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->request->data as $data): ?>
            <tr>
                <td>
                    <?php if ($this->Admin->hasPermission($permissions[\'related\'][\'{model_lower}\'][\'admin_edit\'], $data[\'User\'][\'id\'])): ?>
                        <?= $this->Html->link($data[\'{model}\'][\'title\'], array(
                            \'action\' => \'admin_edit\',
                            $data[\'{model}\'][\'id\']
                        )) ?>
                    <?php else: ?>
                        <?= $data[\'{model}\'][\'title\'] ?>
                    <?php endif ?>
                </td>
                <td>
                    <?php if ($this->Admin->hasPermission($permissions[\'related\'][\'users\'][\'profile\'], $data[\'User\'][\'id\'])): ?>
                        <?= $this->Html->link($data[\'User\'][\'username\'], array(
                            \'controller\' => \'users\',
                            \'action\' => \'profile\',
                            $data[\'User\'][\'username\']
                        )) ?>
                    <?php endif ?>
                </td>
                <td class="hidden-phone">
                    <?= $this->Admin->time($data[\'{model}\'][\'created\']) ?>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (empty($this->request->named[\'trash\'])): ?>
                                <?php if ($this->Admin->hasPermission($permissions[\'related\'][\'{model_lower}\'][\'admin_edit\'], $data[\'User\'][\'id\'])): ?>
                                    <li>
                                        <?= $this->Admin->edit(
                                            $data[\'{model}\'][\'id\']
                                        ) ?>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->Admin->hasPermission($permissions[\'related\'][\'{model_lower}\'][\'admin_delete\'], $data[\'User\'][\'id\'])): ?>
                                    <li>
                                        <?= $this->Admin->delete(
                                            $data[\'{model}\'][\'id\'],
                                            $data[\'{model}\'][\'title\'],
                                            \'{full_name}\'
                                        ) ?>
                                    </li>
                                <?php endif ?>
                            <?php else: ?>
                                <?php if ($this->Admin->hasPermission($permissions[\'related\'][\'{model_lower}\'][\'admin_restore\'], $data[\'User\'][\'id\'])): ?>
                                    <li>
                                        <?= $this->Admin->restore(
                                            $data[\'{model}\'][\'id\'],
                                            $data[\'{model}\'][\'title\']
                                        ) ?>
                                    </li>
                                <?php endif ?>
                                <?php if ($this->Admin->hasPermission($permissions[\'related\'][\'{model_lower}\'][\'admin_delete\'], $data[\'User\'][\'id\'])): ?>
                                    <li>
                                        <?= $this->Admin->delete_perm(
                                            $data[\'{model}\'][\'id\'],
                                            $data[\'{model}\'][\'title\'],
                                            \'{full_name}\'
                                        ) ?>
                                    </li>
                                <?php endif ?>
                            <?php endif ?>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<?= $this->element(\'admin_pagination\') ?>',
					'add.ctp' => '<?php $this->Html->addCrumb(\'Admin\', \'/admin\') ?>
<?php $this->Html->addCrumb(\'{full_name}\', array(\'action\' => \'index\')) ?>
<?php $this->Html->addCrumb(\'Add {model}\', null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Form->create(\'{model}\', array(\'class\' => \'well admin-validate\')) ?>
    <h2>Add {model}</h2>

    <?= $this->Form->input(\'title\', array(\'type\' => \'text\', \'class\' => \'required\')) ?>
    <?= $this->Form->input(\'text\', array(\'type\' => \'textarea\', \'class\' => \'required\')) ?>

<?= $this->Form->end(array(
    \'label\' => \'Submit\',
    \'class\' => \'btn btn-primary\'
)) ?>',
					'edit.ctp' => '<?php $this->Html->addCrumb(\'Admin\', \'/admin\') ?>
<?php $this->Html->addCrumb(\'{full_name}\', array(\'action\' => \'index\')) ?>
<?php $this->Html->addCrumb(\'Edit {model}\', null) ?>

<?php $this->TinyMce->editor() ?>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        \'<i class="icon-chevron-left"></i> Return to Index\',
        array(\'action\' => \'index\'),
        array(\'class\' => \'btn\', \'escape\' => false
    )) ?>
    <?= $this->Html->link(
        \'<i class="icon-trash icon-white"></i> Delete\',
        array(\'action\' => \'delete\', $this->request->data[\'{model}\'][\'id\'], $this->request->data[\'{model}\'][\'title\']),
        array(\'class\' => \'btn btn-danger\', \'escape\' => false, \'onclick\' => "return confirm(\'Are you sure you want to delete this {full_name}?\')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create(\'{model}\', array(\'class\' => \'well admin-validate\')) ?>
    <h2>Edit {model}</h2>

    <?= $this->Form->input(\'title\', array(\'type\' => \'text\', \'class\' => \'required\')) ?>
    <?= $this->Form->input(\'text\', array(\'type\' => \'textarea\', \'class\' => \'required\')) ?>

    <?= $this->Form->hidden(\'id\') ?>

<?= $this->Form->end(array(
    \'label\' => \'Submit\',
    \'class\' => \'btn btn-primary\'
)) ?>'
				),
				'Frontend' => array(
					'index.ctp' => '<?php $this->Html->addCrumb(\'{full_name}\', null) ?>

<?php $this->set(\'title_for_layout\', \'{full_name}\') ?>

<?php if (empty($this->request->data)): ?>
	<p>
		There are no {full_name} at this time.
	</p>
<?php else: ?>
	<ul>
		<?php foreach($this->request->data as $item): ?>
			<li>
				<?= $this->Html->link($item[\'{model}\'][\'title\'], array(
					\'action\' => \'view\',
					$item[\'{model}\'][\'slug\']
				)) ?>
			</li>
		<?php endforeach ?>
	</ul>
<?php endif ?>

<?= $this->Element(\'pagination\') ?>',
					'view.ctp' => '<?php $this->Html->addCrumb(\'{full_name}\', array(\'action\' => \'index\')) ?>
<?php $this->Html->addCrumb($this->request->data[\'{model}\'][\'title\'], null) ?>

<?php $this->set(\'title_for_layout\', $this->request->data[\'{model}\'][\'title\']) ?>

<h1>
	<?= $this->request->data[\'{model}\'][\'title\'] ?>
</h1>

<?= $this->request->data[\'{model}\'][\'text\'] ?>'
				)
			),
			'json' => array(
				'header' => '{
    "title": "{full_name}",
    "api_id": "",
    "current_version": "{current_version}",
    "install": {
    	"block_active": {block_active},
    	"is_fields": {is_fields},
    	"is_searchable": {is_searchable},
    	"model_title": "{model}"',
		'permissions' => ',
				"permissions": {
            "file": "Install/permissions.php",
            "className": "PermissionsInstall",
            "functionName": "generate"
        }',
				'sql' => ',
		"sql": [
            "Install/install_data.sql"
        ]'
			)
		);

		if (file_exists($path . $name))
			$this->rrmdir($path . $name);

		mkdir($path . $name);

		mkdir($path . $name . DS . 'Config');
		file_put_contents($path . $name . DS . 'Config' . DS . 'config.php', str_replace('{name}', $name, stripslashes($defaults['Config']['config.php'])));
		file_put_contents($path . $name . DS . 'Config' . DS . 'routes.php', stripslashes($defaults['Config']['routes.php']));

		mkdir($path . $name . DS . 'Controller');
		if (!empty($data['skeleton']['controller'])) {
			$contents = str_replace('{name}', $controller, $defaults['Controller']['header']);
			if (!empty($data['skeleton']['model'])) {
				$contents .= str_replace('{model}', $model, $defaults['Controller']['with_model']);
			}

			$contents .= '

}';

			file_put_contents($path . $name . DS . 'Controller' . DS . $controller . 'Controller.php', stripslashes($contents));
		}

		mkdir($path . $name . DS . 'Controller' . DS . 'Component');

		mkdir($path . $name . DS . 'Install');
		if (!empty($data['skeleton']['controller']) && !empty($data['skeleton']['model'])) {
			file_put_contents(
				$path . $name . DS . 'Install' . DS .  'permissions.php',
				stripslashes(str_replace('{controller}', $controller, $defaults['Install']['permissions.php']))
			);
		}

		if (!empty($data['skeleton']['model'])) {
			file_put_contents(
				$path . $name . DS . 'Install' . DS .  'install_data.sql',
				stripslashes(str_replace('{model}', strtolower($model), $defaults['Install']['install_data.sql']))
			);
		}

		mkdir($path . $name . DS . 'Model');
		if (!empty($data['skeleton']['model'])) {
			file_put_contents(
				$path . $name . DS . 'Model' . DS . $model . 'AppModel.php',
				stripslashes(str_replace('{model}', $model, $defaults['Model']['appModel']))
			);

			$contents = str_replace('{model}', $model, $defaults['Model']['header']);
			if (!empty($data['basicInfo']['block_active'])) {
				$contents .= str_replace('{model}', $model, $defaults['Model']['block_active']);
			}

			$contents .= '

}';

			file_put_contents($path . $name . DS . 'Model' . DS . $model . '.php', stripslashes($contents));
		}

		mkdir($path . $name . DS . 'View');
		mkdir($path . $name . DS . 'View' . DS . 'Helper');
		mkdir($path . $name . DS . 'View' . DS . 'Admin');
		if (!empty($data['skeleton']['controller']) && !empty($data['skeleton']['model'])) {
			mkdir($path . $name . DS . 'View' . DS . 'Admin' . DS . $controller);

			$find = array(
				'{model}',
				'{model_lower}',
				'{full_name}'
			);
			$replace = array(
				$model,
				strtolower($model),
				Inflector::pluralize($data['basicInfo']['name'])
			);

			file_put_contents(
				$path . $name . DS . 'View' . DS . 'Admin' . DS . $controller . DS . 'index.ctp',
				stripslashes(str_replace($find, $replace, $defaults['View']['Admin']['index.ctp']))
			);
			file_put_contents(
				$path . $name . DS . 'View' . DS . 'Admin' . DS . $controller . DS . 'add.ctp',
				stripslashes(str_replace($find, $replace, $defaults['View']['Admin']['add.ctp']))
			);
			file_put_contents(
				$path . $name . DS . 'View' . DS . 'Admin' . DS . $controller . DS . 'edit.ctp',
				stripslashes(str_replace($find, $replace, $defaults['View']['Admin']['edit.ctp']))
			);
		}

		mkdir($path . $name . DS . 'View' . DS . 'Elements');
		mkdir($path . $name . DS . 'View' . DS . 'Frontend');
		if (!empty($data['skeleton']['controller']) && !empty($data['skeleton']['model'])) {
			mkdir($path . $name . DS . 'View' . DS . 'Frontend' . DS . $controller);

			file_put_contents(
				$path . $name . DS . 'View' . DS . 'Frontend' . DS . $controller . DS . 'index.ctp',
				stripslashes(str_replace($find, $replace, $defaults['View']['Frontend']['index.ctp']))
			);
			file_put_contents(
				$path . $name . DS . 'View' . DS . 'Frontend' . DS . $controller . DS . 'view.ctp',
				stripslashes(str_replace($find, $replace, $defaults['View']['Frontend']['view.ctp']))
			);
		}

		mkdir($path . $name . DS . 'webroot');
		mkdir($path . $name . DS . 'webroot' . DS . 'css');
		mkdir($path . $name . DS . 'webroot' . DS . 'js');
		mkdir($path . $name . DS . 'webroot' . DS . 'img');

		$find = array(
			'{full_name}',
			'{name}',
			'{current_version}',
			'{block_active}',
			'{is_fields}',
			'{is_searchable}',
			'{model}'
		);
		$replace = array(
			$data['basicInfo']['name'],
			$name,
			$data['versions']['current_version'],
			(!empty($data['basicInfo']['block_active']) ? 1 : 0),
			(!empty($data['basicInfo']['is_fields']) ? 1 : 0),
			(!empty($data['basicInfo']['is_searchable']) ? 1 : 0),
			$model
		);

		$json = str_replace($find, $replace, $defaults['json']['header']);

		if (!empty($data['skeleton']['model'])) {
			$json .= $defaults['json']['sql'];
		}

		if (!empty($data['skeleton']['controller']) && !empty($data['skeleton']['model'])) {
			$json .= $defaults['json']['permissions'];
		}

		$json .= '
	},
	"versions": [';

		foreach($data['versions']['versions'] as $version) {
			$json .= '
		"' . $version . '"' . (end($data['versions']['versions']) != $version ? ',' : '');
		}

		$json .= '
	]
}';

		file_put_contents($path . $name . DS . 'plugin.json', $json);
	}
}