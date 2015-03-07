<?php

namespace BlockScore;

use ArrayAccess;
use InvalidArgumentException;

class Object implements ArrayAccess
{

  protected $_values;
  protected $_unsavedValues;

  public function __construct($id = null)
  {
    $this->_values = array();
    $this->_unsavedValues = array();

    if ($id !== null)
    {
      $this->id = $id;
    }
  }

  // Standard accessors
  public function __set($key, $value)
  {
    $this->_values[$key] = $value;

    if ($key !== 'id') {
      $this->_unsavedValues[$key] = $value;
    }
  }

  public function __isset($key)
  {
    return isset($this->_values[$key]);
  }

  public function __unset($key)
  {
    unset($this->_values[$key]);
    unset($this->_unsavedValues[$key]);
  }

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

  public function refreshObject($values)
  {
    foreach($values as $key => $value) {
      unset($this->_unsavedValues[$key]);
      $this->_values[$key] = $value;
    }
  }

  public function getUnsavedValues()
  {
    $params = array();
    foreach ($this->_unsavedValues as $key => $value) {
      if ($value === '') {
        $value = null;
      }

      $params[$key] = $value;
    }

    return $params;
  }
}