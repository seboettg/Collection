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

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\Lists\ToArrayInterface;

interface MapInterface extends Countable, ArrayAccess, IteratorAggregate, ToArrayInterface
{
    /**
     * Overrides internal array with given array.
     *
     * @param array $array
     * @return void
     */
    public function setArray(array $array): void;

    /**
     * Returns a list containing all entries of this map as key-value pairs
     *
     * @return ListInterface
     */
    public function getEntries(): ListInterface;

    /**
     * Returns list containing the keys of the map without their associated values
     *
     * @return ListInterface<scalar>|scalar[]
     */
    public function getKeys(): ListInterface;

    /**
     * Returns a list of the values of this map without their keys
     *
     * @return ListInterface<mixed>
     */
    public function values(): ListInterface;

    /**
     * Alias of count
     *
     * @return int
     */
    public function size(): int;

    /**
     * Removes all elements from this map.
     *
     * @return void
     */
    public function clear(): void;

    /**
     * Checks if the map contains the given key.
     *
     * @param scalar $key
     * @return bool
     */
    public function contains($key): bool;

    /**
     * Alias of contains
     *
     * @param scalar $key
     * @return bool
     */
    public function containsKey($key): bool;

    /**
     * Returns true if the map maps one or more keys to the specified value.
     *
     * @param mixed $value
     * @return bool
     */
    public function containsValue($value): bool;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return bool
     */
    public function isNotEmpty(): bool;

    /**
     * Returns the value corresponding to the given key, or null if such a key is not present in the map.
     *
     * @param scalar $key
     * @return mixed
     */
    public function get($key);

    /**
     * Associates the specified value with the specified key in the map.
     *
     * @param scalar $key
     * @param mixed $value
     * @return void
     */
    public function put($key, $value): void;

    /**
     * Adds all specified entries of the given map to this map.
     *
     * @param MapInterface $map
     * @return void
     */
    public function putAll(MapInterface $map): void;

    /**
     * Removes the specified key and its corresponding value from this map.
     *
     * @param scalar $key
     * @return void
     */
    public function remove($key): void;

    /**
     * Returns true if all entries match the given predicate
     * @param callable $predicate f(Pair<string, mixed>) -> bool
     * @return bool
     */
    public function all(callable $predicate): bool;

    /**
     * Returns true if at least one entry match the given predicate
     * @param callable $predicate f(entry) -> bool
     * @return bool
     */
    public function any(callable $predicate): bool;

    /**
     * Returns a new Map containing all entries matching the given predicate.
     *
     * @param callable|null $predicate <code>f(pair:Pair) -> bool</code> OR <code>f(key: scalar, value:mixed) -> bool</code>
     *                                 Returns true if the given key-value Pair (or given key and value)
     *                                 are matching the given predicate.
     *
     *                                 If the predicate is null, all entries with null values will be removed from the
     *                                 new list.
     * @return MapInterface
     */
    public function filter(callable $predicate = null): MapInterface;

    /**
     * Returns the value for the given key, or the result of the default function if there was no entry for
     * the given key.
     *
     * @param scalar $key
     * @param callable $default f() -> mixed
     * @return mixed
     */
    public function getOrElse($key, callable $default);

    /**
     * Returns a list containing the results of applying the given transform function to each entry in
     * the original map.
     *
     * @param callable $transform f(pair: Pair) -> mixed
     * @return ListInterface<mixed>
     */
    public function map(callable $transform): ListInterface;

    /**
     * Returns a list containing only the non-null results of applying the given transform function
     * to each entry in the original map.
     *
     * @param callable $transform f(pair: Pair) -> mixed
     * @return ListInterface<mixed>
     */
    public function mapNotNull(callable $transform): ListInterface;

    /**
     * Returns a new map containing all elements of the original collection except the elements contained
     * in the given keys list.
     *
     * @param iterable<scalar> $keys
     * @return MapInterface
     */
    public function minus(iterable $keys): MapInterface;

    /**
     * Creates a new map by replacing or adding entries to this map from a given
     * collection of key-value pairs.
     *
     * @param iterable<Pair<scalar, mixed>> $pairs
     * @return MapInterface
     */
    public function plus(iterable $pairs): MapInterface;

    /**
     * Performs the given action on each entry.
     *
     * @param callable $action f(entry: Pair) -> mixed|void
     * @return void
     */
    public function forEach(callable $action): void;

    /**
     * Returns a List containing all key-value pairs.
     *
     * @return ListInterface<Pair<scalar, mixed>>
     */
    public function toList(): ListInterface;

    /**
     * Returns a new map containing all entries from the given collection of pairs.
     *
     * @return MapInterface<scalar, mixed>
     */
    public function toMap(): MapInterface;
}
