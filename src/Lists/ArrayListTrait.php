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
use Seboettg\Collection\Lists\ListFeatures\ListOperationsTrait;
use Seboettg\Collection\Lists\MapFeatures\MapFeaturesTrait;
use Seboettg\Collection\NativePhp\IteratorTrait;
use function Seboettg\Collection\Assert\assertComparable;
use function Seboettg\Collection\Assert\assertStringable;
use function Seboettg\Collection\Common\isComparable;
use function Seboettg\Collection\Common\isScalarOrStringable;
use function Seboettg\Collection\Common\in_array;
use function Seboettg\Collection\Common\strval;

/**
 * @property array $array Base array of this data structure
 */
trait ArrayListTrait
{
    use ListAccessTrait,
        IteratorTrait,
        ListOperationsTrait,
        MapFeaturesTrait;

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
        if (
            isScalarOrStringable($value) /* && $this->all(fn($item) => isScalarOrStringable($item))) */ ||
            isComparable($value) /* && $this->all(fn($item) => isComparable($item))) */
        ) {
            // use custom in_array function
            return in_array($value, $this->array) !== false;
        } else {
            // use PHP's native \in_array function
            return \in_array(
                print_r($value, true),
                array_map(fn($item): string => print_r($item, true), $this->array)
            );
        }
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

        if ($this->all(fn($item): bool => isScalarOrStringable($item))) {
            return listFromArray(array_unique($this->toArray()));
        } else {
            $newList = emptyList();
            $values = $this->array;
            foreach ($values as $value) {
                if (!$newList->contains($value)) {
                    $newList->add($value);
                }
            }
            return $newList;
        }
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
        if (array_key_exists($index, $this->array)) {
            return $this->array[$index];
        }
        return $defaultValue($this);
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
     * Return first element of this list that matches the matchingCondition. If no element matches the condition, null
     * will be returned.
     *
     * @param callable $matchingCondition
     * @return mixed|null
     */
    public function searchBy(callable $matchingCondition)
    {
        $list = listOf(...array_filter($this->array));
        return $list
            ->filter($matchingCondition)
            ->getOrElse(0, fn() => null);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->array;
    }
}
