<?php

/*
 * Copyright (C) 2016 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection;

/**
 * ArrayList
 * @package Seboettg\Collection
 *
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
class ArrayList implements Collection
{

    protected $array;


    public function __construct(array $data = [])
    {
        $this->array = $data;
    }

    public function clear()
    {
        $this->array = [];
        return $this;
    }

    public function get($key)
    {
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }

    public function set($key, $value)
    {
        $this->array[$key] = $value;
        return $this;
    }

    public function setArray(array $array)
    {
        $this->array = $array;
        return $this;
    }

    public function append($value)
    {
        $this->array[] = $value;
    }

    public function add($key, $value)
    {

        if (!array_key_exists($key, $this->array)) {
            $this->array[$key] = $value;
        } elseif (is_array($this->array[$key])) {
            $this->array[$key][] = $value;
        } else {
            $this->array[$key] = [$this->array[$key], $value];
        }

        return $this;
    }


    public function remove($key)
    {
        unset($this->array[$key]);
        return $this;
    }

    /**
     *
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->array);
    }

    /**
     *
     * @param string $value
     *
     * @return mixed
     */
    public function hasValue($value)
    {
        return array_search($value, $this->array, true);
    }

    /**
     *
     * @param array $data
     *
     * @return ArrayList
     */
    public function replace(array $data)
    {
        $this->array = $data;
        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    public function toArray()
    {
        return $this->array;
    }

    public function count()
    {
        return count($this->array);
    }

}
