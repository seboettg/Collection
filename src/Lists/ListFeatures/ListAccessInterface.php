<?php

namespace Seboettg\Collection\Lists\ListFeatures;

interface ListAccessInterface
{
    /**
     * Returns the element of the specified index
     * @param int $index
     * @return mixed|null
     */
    public function get(int $index);

    /**
     * Inserts or replaces the element at the specified position in this list with the specified element.
     *
     * @param int $key
     * @param $element
     * @return void
     */
    public function set(int $key, $element): void;

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
