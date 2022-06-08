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

use Seboettg\Collection\Lists;
use Seboettg\Collection\Lists\ArrayListTrait;
use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\Map;
use Seboettg\Collection\NativePhp\ArrayAccessTrait;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listOf;

/**
 * @property array $array base array of this data structure
 */
trait MapTrait
{
    use ArrayAccessTrait;

    /**
     * @inheritDoc
     */
    public function getEntries(): ListInterface
    {
        return listOf(...array_map(function (string $key, $value) {
            return pair($key, $value);
        }, array_keys($this->array), $this->array));

    }

    /**
     * @inheritDoc
     */
    public function getKeys(): ListInterface
    {
        return listOf(...array_keys($this->array));
    }

    /**
     * @inheritDoc
     */
    public function values(): ListInterface
    {
        return listOf(...array_values($this->array));
    }

    /**
     * {@inheritDoc}
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
     */
    public function clear(): void
    {
        unset($this->array);
        $this->array = [];
    }

    /**
     * @inheritDoc
     * @param scalar $key
     * @return bool
     */
    public function contains($key): bool
    {
        return array_key_exists($key, $this->array);
    }

    /**
     * @inheritDoc
     * @param array $array
     */
    public function setArray(array $array): void
    {
        $this->array = $array;
    }

    /**
     * @inheritDoc
     * @param scalar
     * @return string
     */
    public function containsKey($key): bool
    {
        return $this->contains($key);
    }

    /**
     * @inheritDoc
     * @param mixed $value
     * @return bool
     */
    public function containsValue($value): bool
    {
        return in_array($value, $this->array, true) !== false;
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @inheritDoc
     * @param scalar
     * @return mixed
     */
    public function get($key)
    {
        return $this->array[$key] ?? null;
    }

    /**
     * @inheritDoc
     * @param scalar $key
     * @param mixed $value
     */
    public function put($key, $value): void
    {
        $this->array[$key] = $value;
    }

    /**
     * @param MapInterface<scalar, mixed> $map
     * @return void
     */
    public function putAll(MapInterface $map): void
    {
        foreach ($map as $key => $value) {
            $this->array[$key] = $value;
        }
    }

    /**
     * @inheritDoc
     * @param scalar $key
     * @return void
     */
    public function remove($key): void
    {
        unset($this->array[$key]);
    }

    /**
     * @inheritDoc
     * @param callable $predicate
     * @return bool
     */
    public function all(callable $predicate): bool
    {
        return $this->count() === $this->filter($predicate)->count();
    }

    /**
     * @inheritDoc
     * @param callable $predicate
     * @return bool
     */
    public function any(callable $predicate): bool
    {
        return $this->filter($predicate)->count() > 0;
    }

    /**
     * @inheritDoc
     * @param callable|null $predicate
     * @return MapInterface
     */
    public function filter(callable $predicate = null): MapInterface
    {
        $newInstance = emptyMap();
        $newInstance->array = array_filter($this->array, $predicate);
        return $newInstance;
    }

    /**
     * @inheritDoc
     * @param scalar $key
     * @param callable $default
     * @return mixed
     */
    public function getOrElse($key, callable $default)
    {
        return $this[$key] ?? $default();
    }

    /**
     * @inheritDoc
     * @param callable $transform
     * @return ListInterface
     */
    public function map(callable $transform): ListInterface
    {
        $list = emptyList();
        $list->array = array_map($transform, $this->array);
        return $list;
    }

    /**
     * @inheritDoc
     * @param callable $transform
     * @return ListInterface
     */
    public function mapNotNull(callable $transform): ListInterface
    {
        $newInstance = emptyList();
        $newInstance->setArray(array_values(
            array_filter(array_map($transform, $this->array), function ($item) {
                return $item !== null;
            })
        ));
        return $newInstance;
    }

    /**
     * @inheritDoc
     * @param iterable<scalar> $keys
     * @param MapInterface
     */
    public function minus(iterable $keys): MapInterface
    {
        $newInstance = emptyMap();
        foreach ($this->array as $key => $value) {
            if (!$this->iterableContainsKey($key, $keys)) {
                $newInstance[$key] = $value;
            }
        }
        return $newInstance;
    }

    private function iterableContainsKey(string $key, iterable $keys): bool
    {
        foreach ($keys as $k) {
            if ($k === $key) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     * @param iterable<Pair<scalar, mixed>> $pairs
     * @return MapInterface<scalar, mixed>
     */
    public function plus(iterable $pairs): MapInterface
    {
        $map = emptyMap();
        $map->array = $this->array;
        foreach ($pairs as $pair) {
            $map[$pair->getKey()] = $pair->getValue();
        }
        return $map;
    }

    /**
     * @inheritDoc
     * @param callable $action f(entry: Pair<scalar, mixed>) -> mixed|void
     * @return void
     */
    public function forEach(callable $action): void
    {
        foreach ($this->array as $key => $value) {
            $action(pair($key, $value));
        }
    }

    /**
     * @inheritDoc
     * @return ListInterface<Pair<scalar, mixed>>
     */
    public function toList(): ListInterface
    {
        $list = emptyList();
        foreach ($this->array as $key => $value) {
            $list->add(pair($key, $value));
        }
        return $list;
    }

    public function toMap(): MapInterface
    {
        $newInstance = emptyMap();
        $newInstance->array = $this->array;
        return $newInstance;
    }
}
