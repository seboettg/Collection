<?php
declare(strict_types=1);
/*
 * Copyright (C) 2016 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Comparable;

abstract class Comparator
{

    /**
     * static constant for sorting order ascending
     */
    public const ORDER_ASC = "ASC";

    /**
     * static constant for sorting order descending
     */
    public const ORDER_DESC = "DESC";

    /**
     * static constant for a custom sorting order
     */
    public const ORDER_CUSTOM = "CUSTOM";

    /**
     * defines the order (ascending|descending) for a comparison
     *
     * @var string
     */
    protected $sortingOrder;

    /**
     * object/array which can be used to define a custom order
     * @var mixed
     */
    protected $customOrder;

    /**
     * Comparator constructor.
     * @param string $sortingOrder defines the order (ascending|descending) for a comparison
     * @param mixed $customOrder
     */
    public function __construct(string $sortingOrder = self::ORDER_ASC, $customOrder = null)
    {
        $this->sortingOrder = $sortingOrder;
        $this->customOrder = $customOrder;
    }

    /**
     * Compares its two arguments for order. Returns a negative integer, zero, or a positive integer as the first
     * argument is less than, equal to, or greater than the second.
     *
     * @param Comparable $a
     * @param Comparable $b
     * @return int
     */
    public abstract function compare(Comparable $a, Comparable $b): int;
}
