<?php
declare(strict_types=1);
/*
 * Copyright (C) 2022 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Lists\MapFeatures;

use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\Map\MapInterface;

interface MapFeaturesInterface
{

    /**
     * Splits the original collection into a Map of two entries, where first entry's value (key "first") is a list
     * containing elements for which predicate yielded true, while second entry's value (key "second") is a
     * list containing elements for which predicate yielded false.
     *
     * @param callable $predicate - f(item: mixed) -> bool
     * @return MapInterface<string, ListInterface>
     */
    public function partition(callable $predicate): MapInterface;

    /**
     * Groups elements of the original array by the key returned by the given keySelector function
     * applied to each element and returns a map where each group key is associated with a list of
     * corresponding elements.
     *
     * @param callable $keySelector f(value: mixed) -> scalar – expects the actual value and returns a key
     * @return MapInterface
     */
    public function groupBy(callable $keySelector): MapInterface;

    /**
     * Returns a Map containing key-value pairs provided by transform function applied to elements of the given array.
     * If any of two pairs would have the same key the last one gets added to the map.
     *
     * @param callable $transform f(item: mixed) -> Pair<scalar, mixed>
     * @return MapInterface
     */
    public function associate(callable $transform): MapInterface;

    /**
     * Returns a Map containing the elements from the given collection indexed by the key returned from keySelector
     * function applied to each element.
     *
     * @param callable $keySelector f(value: mixed) -> scalar
     * @return MapInterface
     */
    public function associateBy(callable $keySelector): MapInterface;

    /**
     * Returns a Map where keys are elements from the given collection and values are produced by
     * the valueSelector function applied to each element.
     *
     * List must only contain scalar values
     *
     * @param callable $valueSelector
     * @return MapInterface
     */
    public function associateWith(callable $valueSelector): MapInterface;

    /**
     * If each item of this list is of type Seboettg\Collection\Map\Pair, toMap() returns a Map with
     * key-value associations of the pair objects. If any of the items not of type Pair a WrongTypeException will
     * be thrown.
     *
     * @return MapInterface
     */
    public function toMap(): MapInterface;
}
