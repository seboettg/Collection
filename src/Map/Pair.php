<?php

namespace Seboettg\Collection\Map;

use function Seboettg\Collection\Assert\assertScalar;

final class Pair
{
    /**
     * @var bool|float|int|string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param scalar $key
     * @param mixed $value
     */
    public static function factory($key, $value): Pair
    {
        assertScalar($key, "Key must be a scalar.");
        return new self($key, $value);
    }

    /**
     * @param scalar $key
     * @param mixed $value
     */
    private function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return scalar
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function copy($key = null, $value = null): Pair
    {
        return self::factory(
            $key === null ? $this->getKey() : $key,
            $value === null ? $this->getValue() : $value
        );
    }
}

