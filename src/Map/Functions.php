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

namespace Seboettg\Collection\Map;

use InvalidArgumentException;

final class Functions
{
    /**
     * @return MapInterface
     */
    public static final function emptyMap(): MapInterface
    {
        return new class() implements MapInterface {
            private $array = [];
            use MapTrait;
        };
    }

    /**
     * @param array<Pair> ...$pairs
     * @return MapInterface
     */
    public final static function mapOf(...$pairs): MapInterface
    {
        $map = emptyMap();
        $map->setArray(array_combine(
            array_map(fn (Pair $pair) => $pair->getKey(), $pairs), //keys
            array_map(fn (Pair $pair) => $pair->getValue(), $pairs) //values
        ));
        return $map;
    }

    /**
     * @param scalar $key
     * @param mixed $value
     * @return Pair
     */
    public final static function pair($key, $value): Pair
    {
        return Pair::factory($key, $value);
    }

    public static function isMap($value): bool
    {
        return $value instanceof MapInterface;
    }
}

/**
 * Returns an instance of `MapInterface` containing no entries.
 * @return MapInterface
 */
function emptyMap(): MapInterface
{
    return Functions::emptyMap();
}

/**
 * Returns an implemenation of MapInterface from the passed pairs.
 * @param array<Pair> ...$pairs
 * @return MapInterface
 */
function mapOf(...$pairs): MapInterface
{
    return Functions::mapOf(...$pairs);
}

/**
 * Returns a Pair object from `$key` and `$value`.
 * @param scalar $key
 * @param mixed $value
 * @return Pair
 */
function pair($key, $value): Pair
{
    return Functions::pair($key, $value);
}

/**
 * Returns `true` when the passed `$value` is an instance of `MapInterface`, `false` otherwise.
 * @return bool
 */
function isMap($value): bool
{
    return Functions::isMap($value);
}
