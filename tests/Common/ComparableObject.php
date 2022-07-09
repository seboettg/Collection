<?php

namespace Seboettg\Collection\Test\Common;

use Seboettg\Collection\Comparable\Comparable;

class ComparableObject implements Comparable {
    private string $value;
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function compareTo(Comparable $b): int
    {
        return strcasecmp($this->value, $b->getValue());
    }

    public function getValue(): string
    {
        return $this->value;
    }
}