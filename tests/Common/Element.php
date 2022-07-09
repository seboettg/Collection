<?php

namespace Seboettg\Collection\Test\Common;

class Element
{
    private $attribute1;
    private $attribute2;

    //contructor
    public function __construct($attribute1, $attribute2)
    {
        $this->attribute1 = $attribute1;
        $this->attribute2 = $attribute2;
    }

    // getter
    public function getAttribute1() { return $this->attribute1; }
    public function getAttribute2() { return $this->attribute2; }
}
