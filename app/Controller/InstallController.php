<?php
App::import('Model', 'ConnectionManager');

class InstallController extends AppController
{
	public $name = 'Install';
	public $uses = null;
	public $helpers = array('Form');
	public $components = array('Session');

	public function beforeFilter()
	{
		$this->Security->blackHoleCallback = 'blackhole';
		$this->Auth->allowedActions = array('*');
		$this->layout = 'install';
		$this->viewPath = 'View';
	}

	public function index()
	{	
		$this->set('title_for_layout', 'Install AdaptCMS');

		$this->Session->setFlash('If you have already installed AdaptCMS, please delete the folder located at: '.WWW_ROOT.'installer', 'flash_notice');
	}

	public function database()
	{
		$this->set('title_for_layout', 'Install AdaptCMS :: Database Configuration');

		if (!empty($this->request->data)) {
			$file = APP.'Config/database.php';

        	$new_file = file_get_contents($file);

        	$match = array('{host}', '{login}', '{password}', '{database}', '{prefix}');
        	$replace = array(
        		$this->request->data['host'],
        		$this->request->data['login'],
        		$this->request->data['password'],
        		$this->request->data['database'],
        		$this->request->data['prefix']
        	);

        	$new_file = str_replace($match, $replace,  $new_file);

        	$fh = fopen($file, 'w') or die("can't open file");
        	fwrite($fh, $new_file);
        	fclose($fh);

        	$file_core = APP.'Config/config.php';

        	$core_file = file_get_contents($file_core);

        	/*
			 * Security Salt / cipherSeed
        	*/
        	$match = array(
        		'a668f877ee39dec0ac3c59a91970011538c20c30',
        		'353137383463373366616438373331'
        	);
        	$replace = array(
        		$this->generateSecuritySalt(),
        		$this->generateCipherSeed()
        	);

        	$core_file = str_replace($match, $replace, $core_file);

        	$fhS = fopen($file_core, 'w') or die("can't open file");
        	fwrite($fhS, $core_file);
        	fclose($fhS);

			try {
				$db = ConnectionManager::getDataSource('default');
				if ($db->isConnected()) {
					$this->Session->setFlash('You made a successfull connection to the database.', 'flash_success');
					$this->redirect(array('action' => 'sql'));
				}
			} catch (Exception $e) {
				copy(APP.'Config/database.default.php', $file);

				$this->Session->setFlash('Cannot make connection with the database options you entered.', 'flash_error');
			}
		}
	}

	public function sql()
	{
		$this->set('title_for_layout', 'Install AdaptCMS :: Setup SQL');

		$prefix = ConnectionManager::enumConnectionObjects();

		if (!empty($this->request->data)) {
			$this->set('sql_results', $this->runSQL($prefix['default']['prefix'], "alpha", 1, 1));
		}
	}

	public function account()
	{
		$this->set('title_for_layout', 'Install AdaptCMS :: Create Admin Account');

		$db = ConnectionManager::getDataSource('default');

		$this->loadModel('SettingValue');

		$this->request->data['SecurityQuestions'] = $this->SettingValue->findByTitle('Security Questions');
		$this->request->data['SecurityQuestionOptions'] = $this->SettingValue->findByTitle('Security Question Options');
		$user_status = $this->SettingValue->findByTitle('User Status');

		if (!empty($this->request->data['SecurityQuestionOptions']['SettingValue']['data_options'])) {
			foreach (json_decode($this->request->data['SecurityQuestionOptions']['SettingValue']['data_options']) as $row) {
				$security_options[$row] = $row;
			}
		}

		$this->set(compact('security_options'));

		if (!empty($this->request->data) && !empty($this->request->data['Security'])) {
			$this->loadModel('User');

			$this->request->data['User']['security_answers'] = json_encode($this->request->data['Security']);
			$this->request->data['User']['role_id'] = 1;
			$this->request->data['User']['status'] = 1;

			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('Your admin account was created.', 'flash_success');
				$this->redirect(array('action' => 'finish'));
			} else {
				$this->Session->setFlash('Your admin account could nto be created.', 'flash_error');
			}
		}
	}

	public function finish()
	{
		$this->set('title_for_layout', 'Install AdaptCMS :: Finish');
	}

	public function upgrade()
	{
		$this->set('title_for_layout', 'Install AdaptCMS :: Upgrade');
	}

	public function runSQL($prefix, $version, $data = null, $tables = null)
	{
		$db = ConnectionManager::getDataSource('default');

		$path = WWW_ROOT.'installer'.DS.'sql'.DS;

		if (!empty($tables)) {
			$tables_count1 = 0;
			$tables_count2 = 0;

			$tables_file = file_get_contents($path.'tables-'.$version.'.sql');
			$ex = explode("-- --------------------------------------------------------", $tables_file);

			foreach($ex as $row) {
				$row = str_replace('{prefix}', $prefix, $row);
				
				if ($db->rawQuery($row)) {
					$tables_count1++;
				}

				$tables_count2++;
			}
		}

		if (!empty($data)) {
			$data_count1 = 0;
			$data_count2 = 0;

			$data_file = file_get_contents($path.'data-'.$version.'.sql');
			$ex = explode("-- --------------------------------------------------------", $data_file);

			foreach($ex as $row) {
				$row = str_replace('{prefix}', $prefix, $row);

				if ($db->rawQuery($row)) {
					$data_count1++;
				}

				$data_count2++;
			}
		}

		return array('tables' => array($tables_count1, $tables_count2), 'data' => array($data_count1, $data_count2));
	}

	public function generateCipherSeed()
	{
		$length = 30;

		return substr(mt_rand(9999,99999999) * time().mt_rand(9999,99999999) * time(),0,$length);
	}

	public function generateSecuritySalt() {
		$length = 39;
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZabcdefghijklmnopqrstuvwxyz";
		$real_string_legnth = strlen($characters) - 1;
		$string = "";

		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, $real_string_legnth)];
		}

		return $string;
	}
}