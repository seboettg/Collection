<?php
/*
 * Copyright (C) 2018 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\ArrayList;

use Seboettg\Collection\CollectionInterface;

/**
 * Interface ArrayListInterface
 * @package Seboettg\Collection\ArrayList
 */
interface ArrayListInterface extends CollectionInterface, \Traversable, \IteratorAggregate, \ArrayAccess, \Countable, ToArrayInterface
{

    /**
     * returns element with key $key
     * @param $key
     * @return mixed|null
     */
    public function get($key);

    /**
     * Inserts or replaces the element at the specified position in this list with the specified element.
     *
     * @param $key
     * @param $element
     * @return ArrayListInterface
     */
    public function set($key, $element);

    /**
     * Returns the value of the array element that's currently being pointed to by the
     * internal pointer. It does not move the pointer in any way. If the
     * internal pointer points beyond the end of the elements list or the array is
     * empty, current returns false.
     *
     * @return mixed|false
     */
    public function current();

    /**
     * Advance the internal array pointer of an array.
     * Returns the array value in the next place that's pointed to by the
     * internal array pointer, or false if there are no more elements.
     *
     * @return mixed|false
     */
    public function next();

    /**
     * Rewind the internal array pointer.
     * Returns the array value in the previous place that's pointed to by
     * the internal array pointer, or false if there are no more
     *
     * @return mixed|false
     */
    public function prev();

    /**
     * @param array $array
     * @return mixed
     */
    public function replace(array $array);

    /**
     * Appends the passed element to the end of this list.
     *
     * @param $element
     * @return $this
     */
    public function append($element);

    /**
     * Inserts the specified element at the specified position in this list. If another element already exist at the
     * specified position the affected positions will transformed into a numerated array. As well the existing element
     * as the specified element will be appended to this array.
     *
     * @param $key
     * @param $element
     * @return $this
     */
    public function add($key, $element);

    /**
     * Removes the element at the specified position in this list.
     *
     * @param $key
     * @return $this
     */
    public function remove($key);

    /**
     * Returns true if an element exists on the specified position.
     *
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key);

    /**
     * Returns true if the passed element already exists in this list, otherwise false.
     *
     * @param string $element
     * @return mixed
     */
    public function hasElement($element);

    /**
     * Returns the first element in this list
     * @return mixed
     */
    public function first();

    /**
     * Returns the last element in this list
     * @return mixed
     */
    public function last();
}
