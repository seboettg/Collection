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
use Seboettg\Collection\Map\Pair;
use function Seboettg\Collection\Assert\assertScalar;
use function Seboettg\Collection\Assert\assertType;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Map\emptyMap;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;

/**
 * @property array $array Base array of this data structure
 */
trait MapFeaturesTrait
{

    /**
     * @inheritDoc
     * @param callable $predicate - f(item: mixed) -> bool
     * @return MapInterface<string, ListInterface>
     */
    public function partition(callable $predicate): MapInterface
    {
        $first = listOf(...array_filter($this->array, $predicate));
        return mapOf(
            pair("first", $first),
            pair("second", $this->minus($first))
        );
    }

    /**
     * @inheritDoc
     */
    public function groupBy(callable $keySelector): MapInterface
    {
        $map = emptyMap();
        foreach ($this->array as $value) {
            $key = $keySelector($value);
            if (!$map->contains($key) || !$map[$key] instanceof ListInterface) {
                $map->put($key, emptyList());
            }
            $map->get($key)->add($value);
        }
        return $map;
    }

    /**
     * @inheritDoc
     * @param callable $transform f(item: mixed) -> Pair<scalar, mixed>
     * @return MapInterface
     */
    public function associate(callable $transform): MapInterface
    {
        $map = emptyMap();
        foreach ($this->array as $item) {
            $pair = $transform($item);
            assertType($pair, Pair::class, sprintf(
                "The return value of the callable must be of type %s",
                Pair::class
            ));
            assertScalar($pair->getKey(), "The key of the returned Pair of the callable must be a scalar.");
            $map[$pair->getKey()] = $pair->getValue();
        }
        return $map;
    }

    /**
     * @inheritDoc
     * @param callable $keySelector f(value: mixed) -> scalar
     * @return MapInterface
     */
    public function associateBy(callable $keySelector): MapInterface
    {
        $map = emptyMap();
        foreach ($this->array as $item) {
            $key = $keySelector($item);
            assertScalar($key, "The return value of the callable must be a scalar.");
            $map[$key] = $item;
        }
        return $map;
    }

    /**
     * @inheritDoc
     * @param callable $valueSelector
     * @return MapInterface
     */
    public function associateWith(callable $valueSelector): MapInterface
    {
        $map = emptyMap();
        foreach ($this->array as $item) {
            assertScalar($item,
                "All entries of the list must be scalar values in order to use \"associateWith\".");
            $map[$item] = $valueSelector($item);
        }
        return $map;
    }

    /**
     * @inheritDoc
     */
    public function toMap(): MapInterface
    {
        $result = emptyMap();
        foreach ($this->array as $pair) {
            assertType(
                $pair,
                Pair::class,
                sprintf("Each item of this list must be of type %s.", Pair::class)
            );
            $result[$pair->getKey()] = $pair->getValue();
        }
        return $result;
    }
}
