<?php

namespace Seboettg\Collection\NativePhp;

use ArrayIterator;

/**
 * @property array $array
 */
trait ArrayIteratorTrait
{
    /**
     *
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->array);
    }
}
