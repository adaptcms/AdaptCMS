<?php

class Link extends LinksAppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_plugin_links'
    */
	public $name = 'PluginLink';

    /**
    * A link can have a file (for an image link) and the user who creates/edits a link is assigned to the item.
    */
	public $belongsTo = array(
    	'File' => array(
        	'className'    => 'File',
        	'foreignKey'   => 'file_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
	);

    /**
    * Our validation rules, name of link and URL must be specified.
    */
    public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Link title cannot be empty'
            )
        ),
        'url' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Link URL cannot be empty'
            )
        )
    );

    /**
    * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
    * created the block. This is customizable so you can do a contain of related data if you wish.
    *
    * @return associative array
    */
	public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'Link.deleted_time' => '0000-00-00 00:00:00'
            ),
            'contain' => array(
                'File'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'Link.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['Link.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }

    /**
    * This works in conjuction with the Search feature. With links, we just confirm that the Search can use this.
    * For a more detailed example, checkout the 'Article' model. You can specify conditions, contain, order, etc.
    *
    * @param q string containing search parameter
    * @return associative array
    */
    public function getSearchParams( $q )
    {
        return array(
            true
        );
    }

    /**
    * We get any files and format them accordingly to ensure that any file uploaded is attached to this link.
    * If a link title is not specified, then the title of the link is set. Also any file picked, is attached to the link. (non-uploaded files)
    * 
    * @return true
    */
    public function beforeSave()
    {
        if (!empty($this->data['File']) && !empty($this->data['Files']))
        {
            $this->data['File'] = array_merge($this->data['File'], $this->data['Files']);
        } elseif (!empty($this->data['Files']))
        {
            $this->data['File'] = $this->data['Files'];
        }

        if (!empty($this->data['File']))
        {
            foreach($this->data['File'] as $file)
            {
                $this->data['Link']['file_id'] = $file;
            }
        }

        if (empty($this->data['Link']['link_title']))
        {
            $this->data['Link']['link_title'] = $this->data['Link']['title'];
        }

        return true;
    }
}