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

use Seboettg\Collection\Lists\MapFeatures\MapFeaturesTrait;
use Seboettg\Collection\NativePhp\IteratorTrait;
use function Seboettg\Collection\Assert\assertStringable;

/**
 * @property array $array Base array of this data structure
 */
trait ArrayListTrait
{
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
     * {@inheritdoc}
     */
    public function get(int $index)
    {
        return $this->array[$index] ?? null;
    }

    /**
     * @param $key
     * @param $element
     * @return void
     */
    public function set($key, $element): void
    {
        $this->array[$key] = $element;
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
     * @param $key
     * @return ListInterface|ArrayListTrait
     */
    public function remove($key): ListInterface
    {
        unset($this->array[$key]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($value): bool
    {
        $result = in_array($value, $this->array, true);
        return ($result !== false);
    }

    /**
     * Returns the first element
     * @return mixed
     */
    public function first()
    {
        if ($this->isEmpty()) {
            return null;
        }
        reset($this->array);
        return $this->array[key($this->array)];
    }

    /**
     * Returns the last element
     * @return mixed
     */
    public function last()
    {
        $item = end($this->array);
        reset($this->array);
        return $item;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * Shuffles this list (randomizes the order of the elements in). It uses the PHP function shuffle
     * @see http://php.net/manual/en/function.shuffle.php
     * @return ListInterface|ArrayListTrait
     */
    public function shuffle(): ListInterface
    {
        shuffle($this->array);
        return $this;
    }

    /**
     * @inheritDoc
     * @param ?callable $predicate
     * @param bool $preserveKeys
     * @return ListInterface|ArrayListTrait
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
        $newInstance = emptyList();
        foreach ($this as $value) {
            $newInstance->add($mapFunction($value));
        }
        return $newInstance;
    }

    /**
     * @inheritDoc
     * @param callable $mapFunction
     * @return ListInterface
     */
    public function mapNotNull(callable $mapFunction): ListInterface
    {
        $newInstance = $this->map($mapFunction);
        return $newInstance->filter();
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
     * @param string $delimiter
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function joinToString(string $delimiter, string $prefix = null, string $suffix = null): string
    {
        $result = implode($delimiter, $this->map(function ($item) {
            assertStringable($item, "Elements in list must be convertible to string in order to use joinToString.");
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
     * @param iterable<scalar> $keys
     * @param iterable
     */
    public function minus(iterable $values): ListInterface
    {
        if (!$values instanceof ListInterface) {
            $valuesList = emptyList();
            $valuesList->setArray(is_array($values) ?? $values->toArray());
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
        $newInstance = emptyList();
        $newInstance->setArray(array_intersect($this->array, $list->array));
        return $newInstance;
    }

    /**
     * @inheritDoc
     * @param callable $predicate
     * @return Tuple<ListInterface, ListInterface>
     */
    public function partition(callable $predicate): Tuple
    {
        $tuple = new Tuple(
            emptyList(),
            emptyList()
        );
        $tuple->getFirst()->append(array_filter($this->array, $predicate));
        $tuple->getSecond()->append($this->minus($tuple->getFirst()));
        return $tuple;
    }


    public function plus(iterable $other): ListInterface
    {
        $list = listOf(...$this->array);
        $list->addAll($other);
        return $list;
    }

    public function union(ListInterface $other): ListInterface
    {
        return $this->plus($other);
    }

    public function subtract(ListInterface $other): ListInterface
    {
        $list = emptyList();
        foreach ($this->array as $element) {
            if (!$other->contains($element)) {
                $list->add($element);
            }
        }
        return $list;
    }

    public function any(callable $predicate): bool
    {
        return $this->filter($predicate)->count() > 1;
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
        return listOf(...array_unique($this->array));
    }

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
}
