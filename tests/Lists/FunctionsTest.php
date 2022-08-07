<?php

namespace Seboettg\Collection\Test\Lists;

use PHPUnit\Framework\TestCase;
use Seboettg\Collection\Lists\ListInterface;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\isList;
use function Seboettg\Collection\Lists\listFromArray;
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;

class FunctionsTest extends TestCase
{

    public function test_emptyList_shouldReturnAInstanceOfListInterface()
    {
        $this->assertInstanceOf(ListInterface::class, emptyList());
    }

    public function test_emptyList_shouldReturnAnEmptyList()
    {
        $this->assertCount(0, emptyList());
    }

    public function test_listOf_shouldReturnAListContainingPassedStrings()
    {
        $list = listOf("A", "B", "C");
        $this->assertCount(3, $list);
        $list->forEach(function ($item) {
            $this->assertIsString($item);
        });
    }

    public function test_listFromArray_shouldReturnAListContainingEachElementOfPassedArray()
    {
        $array = ["A", "B", "C"];
        $list = listFromArray($array);
        $this->assertInstanceOf(ListInterface::class, $list);
        $list->forEach(fn ($item) => $this->assertContains($item, $array));
        foreach ($array as $item) {
            $this->assertContains($item, $list);
        }
    }

    public function test_listOf_shouldReturnAInstanceOfListInterface()
    {
        $this->assertInstanceOf(
            ListInterface::class,
            listOf(1, 2, 3)
        );
    }

    public function test_listOf_shouldReturnAListContainingPassedItems()
    {
        $integers = [1, 2, 3];
        $list = listOf(...$integers);
        $this->assertCount(3, $list);
        $list->forEach(function ($item) use ($integers) {
            $this->assertIsInt($item);
            $this->assertContains($item, $integers);
        });
    }

    public function test_isList_shouldReturnTrueIfList()
    {
        $this->assertTrue(isList(mapOf(pair("a", 0), pair("b", 1))->map(
            fn (string $key, int $value) => $value
        )));
    }

    public function test_isList_shouldReturnFalseIfNotList()
    {
        $this->assertFalse(isList(mapOf(pair("a", 0), pair("b", 1))));
    }
}
