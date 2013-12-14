<?php
App::uses('SampleAppModel', 'Sample.Model');
/**
 * Class Sample
 */
class Sample extends SampleAppModel
{
	public $name = 'PluginSample';

    /**
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
     * Our validation rules, name of map.
     */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter in a title'
            )
        )
    );

    /**
     * @var array
     */
    public $actsAs = array('Slug');

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
            'conditions' => array(
                'Sample.deleted_time' => '0000-00-00 00:00:00'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Sample.' . $data['order_by'] . ' ' . $data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Sample.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }
}