<?php

namespace Seboettg\Collection\NativePhp;

use ReturnTypeWillChange;

trait IteratorTrait
{
    private int $offset = 0;
    /**
     * {@inheritdoc}
     */
    #[ReturnTypeWillChange]
    public function current()
    {
        return $this->valid() ? $this->array[$this->offset] : false;
    }

    public function key(): int
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        ++$this->offset;
    }

    public function valid(): bool
    {
        return isset($this->array[$this->offset]);
    }

    public function rewind(): void
    {
        $this->offset = 0;
    }
}
