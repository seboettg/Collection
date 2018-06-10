<?php
/*
 * Copyright (C) 2018 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Stack;

/**
 * Trait StackTrait
 * @package Seboettg\Collection
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
trait StackTrait
{

    /**
     * Pushes an item onto the top of this stack. This has exactly the same effect as:
     * @param mixed $item
     */
    public function push($item)
    {
        $this->array[] = $item;
    }

    /**
     * Removes the object at the top of this stack and returns that object as the value of this function.
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->array);
    }

    /**
     * Returns the object at the top of this stack without removing it from the stack.
     * @return mixed
     */
    public function peek()
    {
        return end($this->array);
    }

    /**
     * Returns the number of items in the stack
     * @return int
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * Returns the position where an item is on this stack. If the passed item occurs as an item in this stack, this
     * method returns the distance from the top of the stack of the occurrence nearest the top of the stack; the topmost
     * item on the stack is considered to be at distance 1. If the passed item does not occur in this stack, this method
     * returns 0.
     *
     * @param $item
     * @return int
     */
    public function search($item)
    {
        $pos = intval(array_search($item, $this->array));
        if (!$pos) {
            return 0;
        }
        return $this->count() - $pos;
    }
}