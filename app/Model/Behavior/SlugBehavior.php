<?php
App::uses('ModelBehavior', 'Model');
/**
 * Slug Behavior File
 *
 * PHP version 5
 *
 * @category App
 * @package  Behavior
 * @author   Charlie Page <charliepage88@gmail.com>
 * @license  Simplified BSD License (http://www.adaptcms.com/pages/license-info)
 * @link     http://www.adaptcms.com
 */
class SlugBehavior extends ModelBehavior
{
    /**
     * @var string
     */
    public $name = 'Slug';

    /**
     * @var array
     */
    private $_defaults = array(
        'slugField' => 'slug',
        'field' => 'title'
    );

    /**
     * Setup
     *
     * @param Model $Model
     * @param array $settings
     *
     * @return void
     */
    public function setup(Model $Model, $settings = array())
    {
        $this->_defaults[$Model->alias]['slugField'] = !empty($settings['slugField']) ? $settings['slugField'] : $this->_defaults['slugField'];
        $this->_defaults[$Model->alias]['field'] = !empty($settings['field']) ? $settings['field'] : $this->_defaults['field'];

        if (!$Model->hasField($this->_defaults[$Model->alias]['slugField']) || !$Model->hasField($this->_defaults[$Model->alias]['field']))
        {
            $Model->Behaviors->disable($this->name);
        }
    }

    /**
     * Before Save
     *
     * @param Model $Model
     * @return mixed|void
     */
    public function beforeSave(Model $Model)
    {
        if (!empty($Model->data[$Model->alias][$this->_defaults[$Model->alias]['field']]))
        {
            $Model->data[$Model->alias][$this->_defaults[$Model->alias]['slugField']] =
                $this->_generateSlug($Model->data[$Model->alias][$this->_defaults[$Model->alias]['field']]);
        }

        return true;
    }

    /**
     * Generate Slug
     *
     * @param $string
     * @return string
     */
    public function _generateSlug($string)
    {
        return strtolower(Inflector::slug($string, "-"));
    }
}