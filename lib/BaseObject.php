<?php

namespace BlockScore;

use ArrayAccess;
use InvalidArgumentException;

/**
 *  This class exists to make it easier to work with objects.
 *  Since we want to interact with objects after retrieving them (edit/delete),
 *    we need to be able to track the original values of the attributes and
 *    also the new values of certain attributes.
 *  Anything extending from this class will also be able to access the original
 *    values by using $var->$value
 */
class BaseObject implements ArrayAccess
{

    // @var array Contains the values of the object.
    protected $_values;

    // @var array Tracks the unsaved values of the object.
    protected $_unsavedValues;

    /**
     * @param string|null $id The BlockScore object ID
     */
    public function __construct($id = null)
    {
        $this->_values = array();
        $this->_unsavedValues = array();

        if ($id !== null) {
            $this->id = $id;
        }
    }

    /**
     * @param string $key
     * @param object $value
     *
     * Sets an object value.
     */
    public function __set($key, $value)
    {
        $this->_values[$key] = $value;

        if ($key !== 'id') {
            $this->_unsavedValues[$key] = $value;
        }
    }

    /**
     * @param string $key
     *
     * @return boolean Whether the key is set.
     */
    public function __isset($key)
    {
        return isset($this->_values[$key]);
    }

    /**
     * @param string $key
     *
     * Unsets an object value given a certain key.
     */
    public function __unset($key)
    {
        unset($this->_values[$key]);
        unset($this->_unsavedValues[$key]);
    }

    /**
     * @param string $key
     *
     * @return object The value at the given key.
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->_values)) {
            return $this->_values[$key];
        }
    }

    // ArrayAccess functions
    public function offsetSet($key, $value)
    {
        $this->$key = $value;
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->_values);
    }

    public function offsetUnset($key)
    {
        unset($this->$key);
    }

    public function offsetGet($key)
    {
        return array_key_exists($key, $this->_values) ? $this->_values[$key] : null;
    }

    /**
     * @param array $values The values to construct the object with
     *
     * @return BaseObject The constructed BaseObject.
     */
    public static function constructObject($values)
    {
        $object = new static(isset($values->id) ? $values->id : null);
        $object->refreshObject($values);
        return $object;
    }

    /**
     * @param array $values The values to "refresh" the object with
     *
     * "Refreshes" the object with the new values.
     */
    public function refreshObject($values)
    {
        foreach($values as $key => $value) {
            unset($this->_unsavedValues[$key]);
            $this->_values[$key] = $value;
        }

        if(isset($values->object) && $values->object == 'candidate') {
            $this->_values['watchlists'] = new Watchlist($values->id);
        }

        if(isset($values->object) && $values->object == 'person') {
            $qs_list = $values->question_sets;
            $this->_values['question_sets'] = new QuestionSet($values->id);
            foreach($qs_list as $i) {
                $this->_values['question_sets']->addExisting(QuestionSet::retrieve($i));
            }
        }
    }

    /**
     * @return array The _values recursively as an array.
     */
    public function toArray()
    {
        return json_decode(json_encode($this->_values), true);
    }

    /**
     * @return array The unsaved values of the object.
     */
    public function getUnsavedValues()
    {
        $params = array();
        foreach ($this->_unsavedValues as $key => $value) {
            if ($value === null) {
                $value = '';
            }

            $params[$key] = $value;
        }

        return $params;
    }
}
