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
 * ArrayList is a useful wrapper class for an array, similar to Java's ArrayList
 * @package Seboettg\Collection
 *
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
class ArrayList implements Collection
{

    /**
     * internal array
     *
     * @var array
     */
    protected $array;

    private $order;

    /**
     * ArrayList constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->array = $data;
    }

    /**
     * flush array list
     *
     * @return $this
     */
    public function clear()
    {
        $this->array = [];
        return $this;
    }

    /**
     * returns element with key $key
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }

    /**
     * Inserts or replaces the element at the specified position in this list with the specified element.
     *
     * @param $key
     * @param $element
     * @return $this
     */
    public function set($key, $element)
    {
        $this->array[$key] = $element;
        return $this;
    }

    /**
     * overrides contents of ArrayList with the contents of $array
     * @param array $array
     * @return $this
     */
    public function setArray(array $array)
    {
        $this->array = $array;
        return $this;
    }

    /**
     * Appends the specified element to the end of this list.
     *
     * @param $element
     * @return $this
     */
    public function append($element)
    {
        $this->array[] = $element;
        return $this;
    }

    /**
     * Inserts the specified element at the specified position in this list. If an other element already exist at the
     * specified position the affected positions will transformed into a numerated array. As well the existing element
     * as the specified element will be appended to this array.
     *
     * @param $key
     * @param $element
     * @return $this
     */
    public function add($key, $element)
    {

        if (!array_key_exists($key, $this->array)) {
            $this->array[$key] = $element;
        } elseif (is_array($this->array[$key])) {
            $this->array[$key][] = $element;
        } else {
            $this->array[$key] = [$this->array[$key], $element];
        }

        return $this;
    }

    /**
     * Removes the element at the specified position in this list.
     *
     * @param $key
     * @return $this
     */
    public function remove($key)
    {
        unset($this->array[$key]);
        return $this;
    }

    /**
     * Returns true if an element exists on the specified position.
     *
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->array);
    }

    /**
     * Returns true if the specified value exists in this list.
     * @param string $value
     *
     * @return mixed
     */
    public function hasValue($value)
    {
        return array_search($value, $this->array, true);
    }

    /**
     * replaces this list by the specified array
     * @param array $data
     *
     * @return ArrayList
     */
    public function replace(array $data)
    {
        $this->array = $data;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? $this->array[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->array;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * Shuffles this list (randomizes the order of the elements in). It uses the PHP function shuffle
     * @see http://php.net/manual/en/function.shuffle.php
     * @return $this
     */
    public function shuffle() {
        shuffle($this->array);
        return $this;
    }

    /*
     * @param $order
     *
    public function sort($order = Comparator::ORDER_ASC)
    {
        Collections::sort($this, new class($order) extends Comparator {


            public function compare(Comparable $a, Comparable $b)
            {
                if ($this->sortingOrder === Comparator::ORDER_ASC) {
                    return $a->compareTo($b);
                }
                return $b->compareTo($a);
            }
        });
    }
    */
}
