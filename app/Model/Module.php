<?php
App::uses('AppModel', 'Model');
/**
 * Class Module
 *
 * @method findById(int $id)
 * @method findByTitle(string $title)
 * @method findByModelTitle(string $title)
 */
class Module extends AppModel {
    public $name = 'Module';

    public $hasMany = array(
        'Block',
        'Cron',
        'Permission',
        'Field'
    );

    public function getIdByTitle($title)
    {
        $data = $this->findByTitle($title);

        return !empty($data['Module']['id']) ? $data['Module']['id'] : 0;
    }

    public function getIdByModelTitle($model_title)
    {
        $data = $this->findByModelTitle($model_title);

        return !empty($data['Module']['id']) ? $data['Module']['id'] : 0;
    }
}