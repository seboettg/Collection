<?php
/*
 * Copyright (C) 2016 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Lists\ListFeatures;

use Seboettg\Collection\Lists\ListInterface;

interface ListOperationsInterface
{


    /**
     * Returns a list containing all elements of the original collection except the elements contained
     * in the given iterable set.
     *
     * @param iterable $elements
     * @return ListInterface
     */
    public function minus(iterable $elements): ListInterface;

    /**
     * Returns a list containing all elements of the original collection and then all elements of
     * the given elements.
     *
     * @param iterable $elements
     * @return ListInterface
     */
    public function plus(iterable $elements): ListInterface;

    /**
     * Returns a list containing all elements that are contained by both this collection and the specified collection.
     *
     * @param ListInterface $other
     * @return ListInterface
     */
    public function intersect(ListInterface $other): ListInterface;
}
