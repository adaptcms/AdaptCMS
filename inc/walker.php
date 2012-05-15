<?php
/**
 * Walker class used to build levels from provided table.
 *
 * @author Marijan Šuflaj <msufflaj32@gmail.com>
 * @link http://php4every1.com
 */
class walker
{
    /**
     * Holds results retreived from database.
     *
     * @var array
     */
    private $_results               = array();

    /**
     * MySQL connection resource
     *
     * @var resource
     */
    protected $_con                 = null;

    /**
     * Array used to map relations betwean entries.
     *
     * @var array
     */
    private $_refMap                = array();

    /**
     * Array used to save traced entries.
     *
     * @var array
     */
    protected $_traced              = array();

    /**
     * Parent field name.
     *
     * @var string
     */
    protected $_parent              = 'parent';

    /**
     * Id field name.
     *
     * @var string
     */
    protected $_id                  = 'id';




    /**
     * Constructor.
     *
     * @param resource $db MySQL database connection resource
     * @throws Exception If $db is not valid MySQL connection resource
     * @return walker Class itself
     */
    public function __construct($db)
    {
        //Is valid resource?
        if (!is_resource($db) || get_resource_type($db) !== 'mysql link')
            throw new Exception('This is not valid MySQL connection resource.');

        $this->_con = $db;
    }

    /**
     * Loads results from database.
     *
     * @param string $sql Sql to execute
     * @throws Exception If could not load results
     * @return walker Class itself
     */
    public function loadResults($sql)
    {
        $this->_results = mysql_query($sql);

        if (!$this->_results)
        	throw new Exception('Could not load result.');


        return $this;
    }

    /**
     * Creates traced entries. Constructs multidimensional array with entries and its childs/parents.
     * Each array key is an object containing two variables ($sefl and $childs). $self contains current
     * entry object retrieved from function mysql_fetch_object. $childs is new array containing what i previously said.
     *
     * @return walker Class itself
     */
    public function trace()
    {
        $temp = array();

        while (($object = mysql_fetch_object($this->_results)) !== false) {
            if ($object->{$this->_parent} > 0) {
                $trace = $this->_traceOrgin($object->{$this->_parent});
                $temp = $this->_place($trace, $object, $temp);
                $this->_refMap[$object->{$this->_id}] = $object->{$this->_parent};
            }
            else {
                $tmpObject = new stdClass();
                $tmpObject->self = $object;
                $tmpObject->childs = array();
                $temp[$object->{$this->_id}] = $tmpObject;
            }
        }

        $this->_traced = $temp;

        return $this;
    }

    /**
     * Finds orgins of an entry (build tree of ID-s so we can find where it goes.
     *
     * @param string $parent Parent ID
     * @param array $array Temp array with ID-s
     * @return array Temp array with ID-s
     */
    private function _traceOrgin($parent, $array = array())
    {
        //If ID exists in our $_refMap
        if (isset($this->_refMap[$parent])) {
        	return array_merge(
        	    array($parent),
        	    $this->_traceOrgin($this->_refMap[$parent], $array)
        	);
        }

        return array($parent);
    }

    /**
     * Finds place for comment (its dimension) and inserts it in a row.
     *
     * @param array $trace Array with entry parents ID-s
     * @param stdClass $object Entry object
     * @param array $temp Temp array
     * @return array Array with inserted row
     */
    private function _place($trace, $object, $temp = array())
    {

        //If there is no more traced ID-s then this is our spot to insert entry
        if (count($trace) === 0) {
            $tmpObject = new stdClass();
            $tmpObject->self = $object;
            $tmpObject->childs = array();
            $temp[$object->{$this->_id}] = $tmpObject;
            return $temp;
        }
        else {
            $key = array_pop($trace);
            $temp[$key]->childs = $this->_place($trace, $object, $temp[$key]->childs);
        }

        return $temp;
    }

    /**
     * Returns traced entries.
     *
     * @return array Traced entries
     */
    public function returnTraced()
    {
        return $this->_traced;
    }

    /**
     * Sets name of filds that is ID of an entry.
     *
     * @param string $field Field name
     * @throws Exception If $field is not string
     * @return walker Class itself
     */
    public function setIdField($field)
    {
        if (!is_string($field))
            throw new Exception('Field must be string.');

        $this->_id = $field;

        return $this;
    }

    /**
     * Sets name of field that is parent of an entry.
     *
     * @param string $field Field name
     * @throws Exception If $field is not string
     * @return walker Class itself
     */
    public function setParentField($field)
    {
        if (!is_string($field))
            throw new Exception('Field must be string.');

        $this->_parent = $field;

        return $this;
    }
}