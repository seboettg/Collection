<?php

namespace Seboettg\Collection\Test\Common;

use function Seboettg\Collection\Common\strval;

class StringableObject {
    private $value;
    public function __construct($value)
    {
        $this->value = $value;
    }
    public function setValue($value) {
        $this->value = $value;
    }
    public function getValue($value) {
        return $value;
    }
    public function __toString(): string {
        return strval($this->value);
    }
}
