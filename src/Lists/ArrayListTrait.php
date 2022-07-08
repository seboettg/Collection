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

use Seboettg\Collection\Comparable\Comparable;
use Seboettg\Collection\Lists\ListFeatures\ListAccessTrait;
use Seboettg\Collection\Lists\MapFeatures\MapFeaturesTrait;
use Seboettg\Collection\Map\MapInterface;
use Seboettg\Collection\NativePhp\IteratorTrait;
use function Seboettg\Collection\Assert\assertComparable;
use function Seboettg\Collection\Assert\assertStringable;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;
use function Seboettg\Collection\Lists\in_array;

/**
 * @property array $array Base array of this data structure
 */
trait ArrayListTrait
{
    use ListAccessTrait;
    use IteratorTrait;
    use MapFeaturesTrait;

    /**
     * flush array list
     *
     * @return ListInterface|ArrayListTrait
     */
    public function clear(): void
    {
        unset($this->array);
        $this->array = [];
    }

    /**
     * Adds the specified element to the end of this list.
     *
     * @param mixed $element
     */
    public function add($element): void
    {
        end($this->array);
        $this->array[] = $element;
    }

    /**
     * @inheritDoc
     */
    public function addAll(iterable $elements): void
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * @inheritDoc
     */
    public function remove($key): void
    {
        unset($this->array[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($value): bool
    {
        if ((isScalarOrStringable($value) && $this->all(fn($item) => isScalarOrStringable($item)))) {
            return in_array($value, $this->array) !== false;
        }
        if (isComparable($value) && $this->all(fn($item) => isComparable($item))) {
            $items = $this->array;
            /** @var Comparable $value */
            /** @var Comparable $item */
            foreach ($items as $item) {
                if ($item->compareTo($value) === 0) {
                    return true;
                }
            }
        } else {
            if ($value instanceof ListInterface && $this->all(fn($item) => $item instanceof ListInterface)) {
                return in_array(print_r($value->toArray(), true), array_map(fn ($item) => print_r($item->toArray(), true), $this->array)) !== false;
            } else {
                return in_array(spl_object_hash($value), array_map(fn($item) => spl_object_hash($item), $this->array));
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function shuffle(): ListInterface
    {
        $array = $this->array;
        shuffle($array);
        return listFromArray($array);
    }

    /**
     * @inheritDoc
     */
    public function filter(?callable $predicate = null, bool $preserveKeys = false): ListInterface
    {
        $list = emptyList();
        $filtered = $predicate == null ? array_filter($this->array) : array_filter($this->array, $predicate);
        $list->setArray(
            $preserveKeys ? $filtered : array_values($filtered)
        );
        return $list;
    }

    /**
     * @param array $array
     */
    public function setArray(array $array): void
    {
        $this->replace($array);
    }

    /**
     * @param array $data
     */
    public function replace(array $data): void
    {
        $this->array = $data;
    }

    /**
     * returns a new ArrayList containing all the elements of this ArrayList after applying the callback function to each one.
     * @param callable $mapFunction
     * @return ListInterface|ArrayListTrait
     */
    public function map(callable $mapFunction): ListInterface
    {
        $list = emptyList();
        foreach ($this as $value) {
            $list->add($mapFunction($value));
        }
        return $list;
    }

    /**
     * @inheritDoc
     * @param callable $mapFunction
     * @return ListInterface
     */
    public function mapNotNull(callable $mapFunction): ListInterface
    {
        $list = $this->map($mapFunction);
        return $list->filter();
    }

    /**
     * Returns a new ArrayList containing a one-dimensional array of all elements of this ArrayList. Keys are going lost.
     * @return ListInterface
     */
    public function flatten(): ListInterface
    {
        $flattenedArray = [];
        array_walk_recursive($this->array, function ($item) use (&$flattenedArray) {
            $flattenedArray[] = $item;
        });
        return listOf(...$flattenedArray);
    }

    /**
     * @inheritDoc
     * @param callable $collectFunction
     * @return mixed
     */
    public function collect(callable $collectFunction)
    {
        return $collectFunction($this->array);
    }

    /**
     * @inheritDoc
     */
    public function joinToString(string $delimiter, string $prefix = null, string $suffix = null): string
    {
        $result = implode($delimiter, $this->map(function ($item) {
            assertStringable(
                $item,
                "All elements in the list must be convertible to string in order to use joinToString."
            );
            return strval($item);
        })->toArray());
        if ($prefix !== null) {
            $result = $prefix . $result;
        }
        if ($suffix !== null) {
            $result = $result . $suffix;
        }
        return $result;
    }

    /**
     * @inheritDoc
     * @deprecated use joinToString instead
     */
    public function collectToString(string $delimiter): string
    {
        return $this->joinToString($delimiter);
    }

    /**
     * @inheritDoc
     * @return int
     */
    public function count(): int
    {
        return count($this->array);
    }

    /**
     * @inheritDoc
     */
    public function size(): int
    {
        return $this->count();
    }

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


    public function plus(iterable $other): ListInterface
    {
        $list = listOf(...$this->array);
        $list->addAll($other);
        return $list;
    }

    /**
     * @inheritDoc
     */
    public function minus(iterable $values): ListInterface
    {
        $valuesList = emptyList();
        if (!$values instanceof ListInterface && is_array($values)) {
            $valuesList->setArray($values);
        } else {
            $valuesList = $values;
        }
        $newInstance = emptyList();
        foreach ($this->array as $value) {
            if (!$valuesList->contains($value)) {
                $newInstance->add($value);
            }
        }
        return $newInstance;
    }

    /**
     * @inheritDoc
     */
    public function intersect(ListInterface $list): ListInterface
    {
        $result = emptyList();
        foreach ($list as $item) {
            if ($this->contains($item)) {
                $result->add($item);
            }
        }
        return $result;
    }

    public function any(callable $predicate): bool
    {
        return $this->filter($predicate)->count() > 0;
    }

    public function all(callable $predicate): bool
    {
        return $this->count() === $this->filter($predicate)->count();
    }

    /**
     * @inheritDoc
     */
    public function chunk(int $size): ListInterface
    {
        $listOfChunks = emptyList();
        $arrayChunks = array_chunk($this->array, $size);
        foreach ($arrayChunks as $arrayChunk) {
            $listOfChunks->add(listOf(...$arrayChunk));
        }
        return $listOfChunks;
    }

    public function distinct(): ListInterface
    {
        $this->forEach(fn($item) => assertComparable($item,
            sprintf(
                "Each item must be of type scalar or implement \Stringable or implement %s",
                Comparable::class
            )
        ));
        $newList = emptyList();
        if ($this->all(fn($item): bool => isScalarOrStringable($item))) {
            return listFromArray(array_unique($this->toArray()));
        } else {
            if ($this->all(fn($item): bool => isComparable($item))) {
                $values = $this->array;
                foreach ($values as $value) {
                    if (!$newList->contains($value)) {
                        $newList->add($value);
                    }
                }
                return $newList;
            }
        }
        return listFromArray($this->array);
    }

    /**
     * @inheritDoc
     */
    public function forEach(callable $action): void
    {
        foreach ($this->array as $element) {
            $action($element);
        }
    }

    /**
     * @inheritDoc
     */
    public function getOrElse(int $index, callable $defaultValue)
    {
        if ($this->array[$index] !== null) {
            return $this->array[$index];
        }
        return $defaultValue();
    }

    /**
     * @inheritDoc
     * @param int $fromIndex
     * @param int $toIndex
     * @return ListInterface
     */
    public function subList(int $fromIndex, int $toIndex): ListInterface
    {
        $list = emptyList();
        for ($i = $fromIndex; $i < $toIndex; ++$i) {
            if (isset($this->array[$i])) {
                $list->add($this->array[$i]);
            }
        }
        return $list;
    }

    /**
     * Return first element of this list that matches the matchingCondition
     *
     * @param callable $matchingCondition
     * @return mixed|null
     */
    public function searchBy(callable $matchingCondition)
    {
        $list = listOf(...array_filter($this->array));
        return $list->filter($matchingCondition)->first();
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->array;
    }
}
