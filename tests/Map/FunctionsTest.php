<?php

namespace Seboettg\Collection\Test\Map;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;

class FunctionsTest extends TestCase
{
    public function testMapOfShouldThrowInvalidArgumentExceptionIfPassedParamsAreNotPairs()
    {
        $this->expectException(InvalidArgumentException::class);
        mapOf(pair("a", "b"), "c");
    }
}
