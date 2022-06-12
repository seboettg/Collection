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
use Seboettg\Collection\Map\MapInterface;
use Traversable;

/**
 * Interface ArrayListInterface
 * @package Seboettg\Collection\ArrayList
 */
interface ListInterface extends CollectionInterface, Traversable, Countable, ToArrayInterface, Iterator
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
     * Returns the first element in this list
     * @return mixed
     */
    public function first();

    /**
     * Returns the last element in this list
     * @return mixed
     */
    public function last();

    /**
     * alias of replace function
     * @param array $array
     * @return void
     */
    public function setArray(array $array): void;

    /**
     * flush array list
     *
     */
    public function clear(): void;

    /**
     * Shuffles this list (randomizes the order of the elements in).
     *
     * @see http://php.net/manual/en/function.shuffle.php
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
     * Groups elements of the original array by the key returned by the given keySelector function
     * applied to each element and returns a map where each group key is associated with a list of
     * corresponding elements.
     *
     * @param callable $keySelector f(value: mixed) -> scalar – expects the actual value and returns a key
     * @return MapInterface
     */
    public function groupBy(callable $keySelector): MapInterface;

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
     * Splits the original collection into a tuple of lists, where first list contains elements for which
     * predicate yielded true, while second list contains elements for which predicate yielded false.
     *
     * @param callable $predicate
     * @return Tuple<ListInterface, ListInterface>
     */
    public function partition(callable $predicate): Tuple;

    /**
     * Returns true if at least one element match the given predicate.
     *
     * @param callable $predicate
     * @return bool
     */
    public function any(callable $predicate): bool;

    /**
     * Returns true if all element match the given predicate.
     *
     * @param callable $predicate
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
     * @param callable $match
     * @return mixed|null
     */
    public function searchBy(callable $match);

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * If each item of this list is of type Seboettg\Collection\Map\Pair, toMap() returns a Map with
     * key-value associations of the pair objects. If any of the items not of type Pair a WrongTypeException will
     * be thrown.
     *
     * @return MapInterface
     */
    public function toMap(): MapInterface;
}
