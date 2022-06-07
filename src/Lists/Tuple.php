<?php

namespace Seboettg\Collection\Lists;

use Seboettg\Collection\Map\Pair;

class Tuple implements TupleInterface
{
    private $first;

    private $second;

    public function __construct($first, $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function getSecond()
    {
        return $this->second;
    }
}
