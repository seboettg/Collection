<?php

namespace Seboettg\Collection\Test;

class TestObject
{
    private $attr1;

    private $attr2;

    public function __construct($attr1, $attr2)
    {
        $this->attr1 = $attr1;
        $this->attr2 = $attr2;
    }

    /**
     * @return mixed
     */
    public function getAttr1()
    {
        return $this->attr1;
    }

    /**
     * @param mixed $attr1
     */
    public function setAttr1($attr1): void
    {
        $this->attr1 = $attr1;
    }


}