<?php

class ToolsController extends AppController
{
	public $name = 'Tools';
	public $uses = array();

	public function admin_index()
	{

	}

	public function admin_clear_cache()
	{
		$total_count = 0;
		$success_count = 0;
		$folders = array('persistent', 'models', 'views');

		foreach($folders as $folder) {
			if (clearCache(null, $folder)) {
				$success_count++;
			}

			$total_count++;
		}

		if ($success_count == $total_count && $success_count > 0) {
			$this->Session->setFlash('Cache has been cleared.', 'flash_success');
		} else {
			$this->Session->setFlash('Cache could not be cleared.', 'flash_error');
		}

		$this->redirect(
			array(
				'action' => 'index'
			)
		);
	}

	public function admin_optimize_database()
	{
		$db = ConnectionManager::getDataSource('default');
		$tables = $db->listSources();

		$this->loadModel('User');

		$messages = array();
		if (!empty($tables)) {
			foreach($tables as $table) {
				$check = $this->User->query('CHECK TABLE ' . $table);
				
				if ($check[0][0]['Msg_text'] == 'OK') {
					$messages[$table]['check'] = 1;
				} else {
					$messages[$table]['check'] = $check[0][0]['Msg_text'];

					$repair = $this->User->query('REPAIR TABLE ' . $table);

					if ($repair[0][0]['Msg_text'] == 'OK') {
						$messages[$table]['repair'] = 1;
					} else {
						$messages[$table]['repair'] = $repair[0][0]['Msg_text'];
					}
				}

				if ($messages[$table]['check'] == 1 || !empty($messages[$table]['repair']) && $messages[$table]['repair'] == 1) {
					$analyze = $this->User->query('ANALYZE TABLE ' . $table);

					if ($analyze[0][0]['Msg_text'] == 'Table is already up to date') {
						$messages[$table]['analyze'] = 1;
					} else {
						$messages[$table]['analyze'] = $analyze[0][0]['Msg_text'];

						$optimize = $this->User->query('OPTIMIZE TABLE ' . $table);

						if ($optimize[0][0]['Msg_text'] == 'OK' || $optimize[0][0]['Msg_text'] == 'Table is already up to date') {
							$messages[$table]['optimize'] = 1;
						} else {
							$messages[$table]['optimize'] = $optimize[0][0]['Msg_text'];
						}
					}
				}
			}
		}

		$this->set(compact('messages'));
	}
}