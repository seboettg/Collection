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

use ReflectionException;
use ReflectionFunction;
use Seboettg\Collection\Assert\Exception\NotApplicableCallableException;
use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\NativePhp\ArrayAccessTrait;
use Seboettg\Collection\NativePhp\ArrayIteratorTrait;
use function Seboettg\Collection\Assert\assertScalar;
use function Seboettg\Collection\Assert\assertType;
use function Seboettg\Collection\Assert\assertValidCallable;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Common\in_array;
use function Seboettg\Collection\Common\isComparable;
use function Seboettg\Collection\Common\isScalarOrStringable;

/**
 * @property array $array base array of this data structure
 */
trait MapTrait
{
    use ArrayAccessTrait, ArrayIteratorTrait;

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
        assertScalar($key, "Key must be a scalar value");
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
        if (
            isScalarOrStringable($value) /* && $this->all(fn ($_, $value): bool => is_scalar($value)) */ ||
            isComparable($value) /* && $this->all(fn ($_, $value): bool => isComparable($value)) */
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
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
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
        assertScalar($key, "Key must be a scalar value");
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
        $map = emptyMap();
        if ($predicate !== null) {
            try {
                $reflected = new ReflectionFunction($predicate);
                if (count($reflected->getParameters()) === 1) {
                    assertValidCallable($predicate, [Pair::class]);
                    foreach ($this->array as $key => $value) {
                        if ($predicate(pair($key, $value)) === true) {
                            $map->put($key, $value);
                        }
                    }
                } else {
                    if (count($reflected->getParameters()) === 2) {
                        assertValidCallable($predicate, ["scalar", "mixed"]);
                    }
                    foreach ($this->array as $key => $value) {
                        if ($predicate($key, $value) === true) {
                            $map->put($key, $value);
                        }
                    }
                }
            } catch (ReflectionException $ex) {
                throw new NotApplicableCallableException("Invalid callable passed.");
            }
        } else {
            $map->array = array_filter($this->array);
        }
        return $map;
    }

    /**
     * @inheritDoc
     * @param scalar $key
     * @param callable $default
     * @return mixed
     */
    public function getOrElse($key, callable $default)
    {
        return $this[$key] ?? $default($this);
    }

    /**
     * @inheritDoc
     * @param callable $transform
     * @return ListInterface
     */
    public function map(callable $transform): ListInterface
    {
        $list = emptyList();
        try {
            $reflected = new ReflectionFunction($transform);
            if (count($reflected->getParameters()) === 1) {
                assertValidCallable($transform, [Pair::class]);
                foreach ($this->array as $key => $value) {
                    $list->add($transform(pair($key, $value)));
                }
            } else {
                if (count($reflected->getParameters()) === 2) {
                    assertValidCallable($transform, ["scalar", "mixed"]);
                }
                foreach ($this->array as $key => $value) {
                    $list->add($transform($key, $value));
                }
            }
        } catch (ReflectionException $ex) {
            throw new NotApplicableCallableException("Invalid callable passed.");
        }
        return $list;
    }

    /**
     * @inheritDoc
     * @param callable $transform
     * @return ListInterface
     */
    public function mapNotNull(callable $transform): ListInterface
    {
        return $this->map($transform)->filter(fn($item) => $item !== null);
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

    private function iterableContainsKey($key, iterable $keys): bool
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
        if ($pairs instanceof MapInterface) {
            foreach ($pairs as $key => $value) {
                $map[$key] = $value;
            }
        } else {
            foreach ($pairs as $pair) {
                assertType($pair, Pair::class,
                    sprintf(
                        "Expected object of type %s, object of type %s given",
                        Pair::class,
                        gettype($pair) === "object" ? get_class($pair) : gettype($pair)
                    )
                );
                $map[$pair->getKey()] = $pair->getValue();
            }
        }
        return $map;
    }

    /**
     * @inheritDoc
     * @param callable $action f(entry: Pair<scalar, mixed>) -> mixed|void OR f(key: scalar, value: mixed>) -> mixed|void
     * @return void
     */
    public function forEach(callable $action): void
    {
        try {
            $reflected = new ReflectionFunction($action);
            if (count($reflected->getParameters()) === 1) {
                assertValidCallable($action, [Pair::class]);
                foreach ($this->array as $key => $value) {
                    $action(pair($key, $value));
                }
            } else {
                if (count($reflected->getParameters()) === 2) {
                    assertValidCallable($action, ["scalar", "mixed"]);
                }
                foreach ($this->array as $key => $value) {
                    $action($key, $value);
                }
            }
        } catch (ReflectionException $ex) {
            throw new NotApplicableCallableException("Invalid callable passed.");
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
        return mapOf(...$this->toList());
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->array;
    }
}
