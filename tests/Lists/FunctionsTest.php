<?php

namespace Seboettg\Collection\Test\Lists;

use PHPUnit\Framework\TestCase;
use Seboettg\Collection\Lists\ListInterface;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listFromArray;
use function Seboettg\Collection\Lists\listOf;

class FunctionsTest extends TestCase
{

    public function testEmptyListShouldReturnAInstanceOfListInterface()
    {
        $this->assertInstanceOf(ListInterface::class, emptyList());
    }

    public function testListOfShouldReturnAListContainingPassedStrings()
    {
        $list = listOf("A", "B", "C");
        $this->assertCount(3, $list);
        $list->forEach(function ($item) {
            $this->assertIsString($item);
        });
    }

    public function testListFromArrayShouldReturnAListContainingEachElementOfPassedArray()
    {
        $array = ["A", "B", "C"];
        $list = listFromArray($array);
        $this->assertInstanceOf(ListInterface::class, $list);
        $list->forEach(fn ($item) => $this->assertContains($item, $array));
        foreach ($array as $item) {
            $this->assertContains($item, $list);
        }
    }

    public function testListOfShouldReturnAInstanceOfListInterface()
    {
        $this->assertInstanceOf(
            ListInterface::class,
            listOf(1, 2, 3)
        );
    }

    public function testListOfShouldReturnAListContainingPassedItems()
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
