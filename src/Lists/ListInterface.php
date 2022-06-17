<?php
declare(strict_types=1);
/*
 * Copyright (C) 2018 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Lists;

use Countable;
use Iterator;
use Seboettg\Collection\Assert\Exception\NotConvertibleToStringException;
use Seboettg\Collection\CollectionInterface;
use Seboettg\Collection\Lists\ListFeatures\ListAccessInterface;
use Seboettg\Collection\Lists\MapFeatures\MapFeaturesInterface;
use Seboettg\Collection\Map\MapInterface;
use Traversable;

/**
 * Interface ArrayListInterface
 * @package Seboettg\Collection\ArrayList
 */
interface ListInterface extends
    CollectionInterface, ListAccessInterface, Traversable, Countable, ToArrayInterface, Iterator, MapFeaturesInterface
{
    /**
     * alias of replace function
     * @param array $array
     * @return void
     */
    public function setArray(array $array): void;

    /**
     * @param array $array
     * @return void
     */
    public function replace(array $array): void;

    /**
     * Appends the passed element to the end of this list.
     *
     * @param $element
     * @return void
     */
    public function add($element): void;

    /**
     * Appends all passed elements to the end of this list.
     *
     * @param iterable $elements
     * @return void
     */
    public function addAll(iterable $elements): void;

    /**
     * Removes the element at the specified position in this list.
     *
     * @param $value
     * @return ListInterface
     */
    public function remove($value): ListInterface;

    /**
     * Returns true if the passed element already exists in this list, otherwise false.
     *
     * @param mixed $element
     * @return bool
     */
    public function contains($element): bool;

    /**
     * flush array list
     *
     */
    public function clear(): void;

    /**
     * Shuffles this list (randomizes the order of the elements in).
     *
     * @return ListInterface
     */
    public function shuffle(): ListInterface;

    /**
     * Returns a list containing only elements matching the given predicate.
     *
     * @param ?callable|null $predicate
     * @param bool $preserveKeys default false
     * @return ListInterface
     */
    public function filter(?callable $predicate = null, bool $preserveKeys = false): ListInterface;

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
     * returns a new ArrayList containing all the elements of this ArrayList after applying the callback function to each one.
     * @param callable $mapFunction
     * @return ListInterface
     */
    public function map(callable $mapFunction): ListInterface;

    /**
     * Same as <code>map</code> but removes null values from the new list
     * @param callable $mapFunction
     * @return ListInterface
     */
    public function mapNotNull(callable $mapFunction): ListInterface;

    /**
     * Returns a new ArrayList of all elements from all collections in the given collection.
     * @return ListInterface
     */
    public function flatten(): ListInterface;

    /**
     * Expects a callable function which collects the elements of this list and returns any object. The callable
     * function gets passed the entire array of the list
     *
     * @param callable $collectionFunction f(array) -> mixed
     * @return mixed
     */
    public function collect(callable $collectionFunction);

    /**
     * @deprecated use joinToString instead
     */
    public function collectToString(string $delimiter): string;

    /**
     * Tries to convert each element of the list to a string and concatenates them with given delimiter.
     * Throws a <code>NotConvertibleToStringException</code> if any of the objects in the list is not a
     * string or is not convertible to string.
     *
     * @param string $delimiter
     * @param string|null $prefix
     * @param string|null $suffix
     * @return string
     * @throws NotConvertibleToStringException
     */
    public function joinToString(string $delimiter, string $prefix = null, string $suffix = null): string;

    /**
     * Alias for <code>count()</code>
     * @return int
     */
    public function size(): int;

    /**
     * Returns a list containing all elements that are contained by both this collection and the specified collection.
     *
     * @param ListInterface $other
     * @return ListInterface
     */
    public function intersect(ListInterface $other): ListInterface;

    /**
     * Returns a list containing all distinct elements from both collections.
     *
     * @param ListInterface $other
     * @return mixed
     */
    public function union(ListInterface $other): ListInterface;

    /**
     * Returns a list containing all elements that are contained by this list and not contained
     * by the specified list.
     *
     * @param ListInterface $other
     * @return ListInterface
     */
    public function subtract(ListInterface $other): ListInterface;

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
     * Returns true if at least one element match the given predicate.
     *
     * @param callable $predicate f(item) -> bool
     * @return bool
     */
    public function any(callable $predicate): bool;

    /**
     * Returns true if all element match the given predicate.
     *
     * @param callable $predicate f(item) -> bool
     * @return bool
     */
    public function all(callable $predicate): bool;

    /**
     * Splits this collection into a list of lists each not exceeding the given size.
     *
     * @param int $size
     * @return ListInterface<ListInterface<mixed>>
     */
    public function chunk(int $size): ListInterface;

    /**
     * Returns a list containing only distinct elements from the given array.
     *
     * @return ListInterface<mixed>
     */
    public function distinct(): ListInterface;

    /**
     * @param callable $action f(element: mixed) -> void|mixed
     * @return void
     */
    public function forEach(callable $action): void;

    /**
     * Returns an element at the given index or the result of calling the defaultValue
     * function if the index is out of bounds of this list.
     *
     * @param int $index
     * @param callable $defaultValue - f() -> mixed
     * @return mixed
     */
    public function getOrElse(int $index, callable $defaultValue);

    /**
     * Returns a new list of this list between the specified fromIndex (inclusive) and toIndex (exclusive).
     *
     * @param int $fromIndex
     * @param int $toIndex
     * @return ListInterface
     */
    public function subList(int $fromIndex, int $toIndex): ListInterface;

    /**
     * @param callable $match
     * @return mixed|null
     */
    public function searchBy(callable $match);

    /**
     * @return bool
     */
    public function isEmpty(): bool;
}
