<?php

namespace Seboettg\Collection\Test\Map;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Seboettg\Collection\Map\MapInterface;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;

class FunctionsTest extends TestCase
{
    public function testMapOfShouldCreateMap()
    {
        $map = mapOf(pair("a", "b"), pair("c", "d"));
        $this->assertInstanceOf(MapInterface::class, $map);
        $this->assertCount(2, $map);
    }
}
