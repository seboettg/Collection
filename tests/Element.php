<?php

namespace Seboettg\Collection\Test;

use Seboettg\Collection\Comparable\Comparable;

class Element implements Comparable
{

    /**
     * @var string
     */
    private string $attr1;

    /**
     * @var string
     */
    private string $attr2;

    public function __construct(string $attr1, string $attr2 = "")
    {
        $this->attr1 = $attr1;
        $this->attr2 = $attr2;
    }

    /**
     * @return mixed
     */
    public function getAttr1(): string
    {
        return $this->attr1;
    }

    /**
     * @param string $attr1
     */
    public function setAttr1(string $attr1)
    {
        $this->attr1 = $attr1;
    }

    /**
     * @return string
     */
    public function getAttr2(): string
    {
        return $this->attr2;
    }

    /**
     * @param string $attr2
     */
    public function setAttr2(string $attr2)
    {
        $this->attr2 = $attr2;
    }

    /**
     * Compares this object with the specified object for order. Returns a negative integer, zero, or a positive
     * integer as this object is less than, equal to, or greater than the specified object.
     *
     * The implementor must ensure sgn(x.compareTo(y)) == -sgn(y.compareTo(x)) for all x and y.
     *
     * @param Comparable $b
     * @return int
     */
    public function compareTo(Comparable $b): int
    {
        /** @var Element $b */
        return strcmp($this->attr1 . $this->attr2, $b->attr1 . $b->attr2);
    }
}
