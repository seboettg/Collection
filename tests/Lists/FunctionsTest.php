<?php

namespace Seboettg\Collection\Test\Lists;

use PHPUnit\Framework\TestCase;
use Seboettg\Collection\Lists\ListInterface;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listOf;

class FunctionsTest extends TestCase
{

    public function testEmptyListShouldReturnAInstanceOfListInterface()
    {
        $list = emptyList();
        $this->assertInstanceOf(ListInterface::class, $list);
    }

    public function testListOfShouldReturnAListContainingPassedStrings()
    {
        $list = listOf("A", "B", "C");
        $this->assertCount(3, $list);
        $list->forEach(function ($item) {
            $this->assertIsString($item);
        });
    }

    public function testListOfShouldReturnAListContainingPassedIntegers()
    {
        $integers = [1, 2, 3];
        $list = listOf(...$integers);
        $this->assertCount(3, $list);
        $list->forEach(function ($item) use ($integers) {
            $this->assertIsInt($item);
            $this->assertContains($item, $integers);
        });
    }
}
