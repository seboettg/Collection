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
 * Abstract class Comparator. If extending this class the compare function must be implemented. compare() is a
 * comparison function, which imposes a total ordering on some collection of objects. Comparators can be passed to a
 * sort method to allow precise control over the sort order.
 *
 * @package Seboettg\Collection
 *
 * @author Sebastian Böttger <seboettg@gmail.de>
 */
abstract class Comparator
{

    /**
     * static constant for sorting order ascending
     */
    const ORDER_ASC = "ASC";

    /**
     * static constant for sorting order descending
     */
    const ORDER_DESC = "DESC";

    /**
     * defines the order (ascending|descending) for a comparison
     *
     * @var string
     */
    protected $sortingOrder;


    /**
     * Comparator constructor.
     * @param string $sortingOrder defines the order (ascending|descending) for a comparison
     */
    public function __construct($sortingOrder = self::ORDER_ASC)
    {
        $this->sortingOrder = $sortingOrder;
    }

    /**
     * Compares its two arguments for order. Returns a negative integer, zero, or a positive integer as the first
     * argument is less than, equal to, or greater than the second.
     *
     * @param Comparable $a
     * @param Comparable $b
     * @return int
     */
    public abstract function compare(Comparable $a, Comparable $b);
}
