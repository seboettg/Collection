<?php

namespace Seboettg\Collection\Map;

use function Seboettg\Collection\Assert\assertScalar;

class Pair
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
    public function __construct($key, $value)
    {
        assertScalar($key, "Key must be a scalar.");
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
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
