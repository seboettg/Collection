<?php

namespace Seboettg\Collection\Test\Map;

use PHPUnit\Framework\TestCase;
use Seboettg\Collection\Map\MapInterface;
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Map\emptyMap;
use function Seboettg\Collection\Map\isMap;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;

class FunctionsTest extends TestCase
{
    public function test_emptyMap_shouldReturnAnInstanceOfMapInterface()
    {
        $this->assertInstanceOf(MapInterface::class, emptyMap());
    }

    public function test_emptyMap_shouldReturnAnEmptyMap()
    {
        $this->assertCount(0, emptyMap());
    }

    public function test_mapOf_shouldCreateMap()
    {
        $map = mapOf(pair("a", "b"), pair("c", "d"));
        $this->assertInstanceOf(MapInterface::class, $map);
        $this->assertCount(2, $map);
    }

    public function test_isMap_shouldReturnTrueIfMap()
    {
        $this->assertTrue(
            isMap(listOf(pair("a", 1), pair("b", 2))->toMap())
        );
    }

    public function test_isMap_shouldReturnFalseIfNotAMap()
    {
        $this->assertFalse(
            isMap(listOf(pair("a", 1), pair("b", 2)))
        );
    }
}
