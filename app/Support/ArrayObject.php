<?php namespace App\Support;

use ArrayAccess;
use Iterator;
use Countable;

class ArrayObject implements ArrayAccess, Iterator, Countable
{
    protected $container = [];

    public function __set($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function __get($key)
    {
        return array_get($this, $key);
    }

    public function count()
    {
        return count($this->container);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return array_get($this->container, $offset, new self);
    }

    public function rewind()
    {
        reset($this->container);
    }

    public function current()
    {
        return current($this->container);
    }

    public function key() 
    {
        return key($this->container);
    }

    public function next() 
    {
        return next($this->container);
    }

    public function valid()
    {
        $key = key($this->container);
        return ($key !== NULL && $key !== FALSE);
    }

    public function __toString()
    {
        return '';
    }

    public function toArray()
    {
        return $this->container;
    }

    public function toArrayRecursively()
    {
        $array = $this->container;

        array_walk_recursive($array, function (&$property) {
            if (is_object($property) && method_exists($property, 'toArray')) {
                $property = $property->toArrayRecursively();
            }
        });

        return $array;
    }
}

