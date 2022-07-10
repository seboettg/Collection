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

namespace Seboettg\Collection;

use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\Comparable\Comparator;

/**
 * Class Collections
 * @package Seboettg\Collection
 *
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
final class Collections
{
    /**
     * Sorts the specified list according to the order induced by the specified comparator. All elements in the list
     * must be mutually comparable.
     *
     * @param ListInterface $list
     * @param Comparator $comparator
     * @return ListInterface
     */
    public static function sort(ListInterface $list, Comparator $comparator): ListInterface
    {
        $array = $list->toArray();
        usort($array, [$comparator, "compare"]);
        $list->replace($array);
        return $list;
    }
}
