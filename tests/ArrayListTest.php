<?php
/*
 * Copyright (C) 2016 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Seboettg\Collection\ArrayList;
use Seboettg\Collection\Assert\Exception\NotConvertibleToStringException;
use Seboettg\Collection\Collections;
use Seboettg\Collection\Comparable\Comparable;
use Seboettg\Collection\Comparable\Comparator;
use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\Stack;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Lists\strval;

class ArrayListTest extends TestCase
{

    /**
     * @var ListInterface
     */
    private ListInterface $arrayList;


    public function setUp(): void
    {
        $this->arrayList = new ArrayList(
            new Element("a", "aa"),
            new Element("b", "bb"),
            new Element("c", "cc"),
            new Element("k", "kk"),
            new Element("d", "dd")
        );
    }

    public function testCurrent()
    {
        $this->assertTrue($this->arrayList->current()->getAttr2() === "aa");
        $arrayList = emptyList();
        $this->assertFalse($arrayList->current());
    }

    public function testAdd()
    {
        $i = $this->arrayList->count();
        $this->arrayList->add(new Element("3", "33"));
        $j = $this->arrayList->count();
        $this->assertEquals($i + 1, $j);
        /** @var Element $eI */
        $eI = $this->arrayList->toArray()[$i];
        $this->assertEquals("3", $eI->getAttr1());
    }

    public function testReplace()
    {
        $array = ["x", "y", "z"];
        $this->arrayList->replace($array);
        foreach ($array as $key => $value) {
            $this->assertEquals($value, $this->arrayList->get($key));
        }
    }

    public function testClear()
    {
        $this->assertTrue($this->arrayList->count() > 0);
        $this->assertEquals(0, $this->arrayList->clear()->count());
    }

    public function testSetArray()
    {
        $this->arrayList->setArray([1,2,3,4,5,6]);
        $keys = array_keys($this->arrayList->toArray());
        foreach ($keys as $key) {
            $this->assertIsInt($key);
            $this->assertNotEmpty($this->arrayList->get($key));
            $this->assertTrue($this->arrayList->get($key) === $key + 1);
        }
    }

    public function testShuffle()
    {
        $this->arrayList
            ->addAll([
                new Element("x", "xx"),
                new Element("y", "yy"),
                new Element("z", "zz")
            ]);

        Collections::sort($this->arrayList, new class extends Comparator{
            public function compare(Comparable $a, Comparable $b): int {
                return $a->compareTo($b);
            }
        });
        $lte = false;
        for ($i = 0; $i < $this->arrayList->count() - 1; ++$i) {
            /** @var Element $elemI */
            $elemI = $this->arrayList->get($i);
            /** @var Element $elemtI1 */
            $elemtI1 = $this->arrayList->get($i + 1);
            $lte = ($elemI->getAttr1() <= $elemtI1->getAttr1());
            if (!$lte) {
                break;
            }
        }
        //each element on position $i is smaller than or equal to the element on position $i+1
        $this->assertTrue($lte);

        $arr1 = $this->arrayList->toArray();
        $this->arrayList->shuffle();
        $arr2 = $this->arrayList->toArray(); //shuffled array

        $equal = false;
        // at least one element has another position as before
        for ($i = 0; $i < count($arr1); ++$i) {
            /** @var Element $elem1 */
            $elem1 = $arr1[$i];
            /** @var Element $elem2 */
            $elem2 = $arr2[$i];
            $equal = ($elem1->getAttr1() == $elem2->getAttr1());
            if (!$equal) {
                break;
            }
        }
        $this->assertFalse($equal);
    }

    public function testContains()
    {
        $list = new ArrayList("a", "b", "c");

        $this->assertTrue($list->contains("a"));
    }

    /**
     * @throws Exception
     */
    public function testIterator()
    {
        foreach ($this->arrayList as $key => $item) {
            $this->assertTrue(is_int($key));
            $this->assertInstanceOf(Element::class, $item);
        }
    }

    public function testRemove()
    {
        $list = listOf("a", "b", "c");
        $list->add("d");
        $this->assertTrue($list->contains("d"));
        $list->remove(0);
        $this->assertFalse($list->contains("a"));
    }


    public function testFirst()
    {
        $this->assertEquals("a", $this->arrayList->first()->getAttr1());
        $this->assertEquals("d", $this->arrayList->last()->getAttr1());
    }

    public function testFilter()
    {
        $this->assertEquals(
            listOf("a", "c", "e", "g", "i"),
            listOf("a", "b", "c", "d", "e", "f", "g", "h", "i", "j")
                ->filter(fn ($letter) => ord($letter) % 2 !== 0)
        );
    }

    public function testMap()
    {
        $cubic = function($i) {
            return $i * $i * $i;
        };
        $list = new ArrayList(1, 2, 3, 4, 5);
        $cubicList = $list->map($cubic);
        $this->assertEquals(listOf(1, 8, 27, 64, 125), $cubicList);

        $list = new ArrayList('a', 'b', 'c');
        $toUpper = $list->map(function($item) { return ucfirst($item); });
        $this->assertEquals(listOf('A', 'B', 'C'), $toUpper);
    }

    public function testMapNotNull()
    {
        $list = new ArrayList(1, 2, 3, 4, 5);
        $this->assertEquals(listOf(1, 3, 5), $list->mapNotNull(function($item) {
            return $item % 2 !== 0 ? $item : null;
        }));
    }

    public function testFlatten()
    {
        $list = new ArrayList([['a', 'b'], 'c']);
        $this->assertEquals(['a', 'b', 'c'], $list->flatten()->toArray());
        $list = new ArrayList(["term" => ['a', 'b'], 'c']);
        $this->assertEquals(['a', 'b', 'c'], $list->flatten()->toArray());
    }


    public function testCollect()
    {
        $arrayList = new ArrayList('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
        /** @var Stack $stack */
        $stack = $arrayList
            ->collect(function(array $list) {
                $result = new Stack();
                foreach ($list as $item) {
                    $result->push($item);
                }
                return $result;
            });
        $this->assertEquals(8, $stack->count());
        $this->assertTrue('h' == $stack->pop());
    }

    /**
     * @throws \Seboettg\Collection\Exception\NotConvertibleToStringException
     */
    public function testJoinToString()
    {
        $arrayList = new ArrayList('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
        $result = $arrayList->joinToString(", ");
        $this->assertEquals("a, b, c, d, e, f, g, h", $result);
    }

    /**
     * @throws \Seboettg\Collection\Exception\NotConvertibleToStringException
     */
    public function testJoinToStringWithDoubleValues()
    {
        $arrayList = new ArrayList(1.0, 1.1, 1.2, 1.3);
        $result = $arrayList->joinToString("; ");
        $this->assertEquals("1.0; 1.1; 1.2; 1.3", $result);
    }

    /**
     */
    public function testJoinToStringWithToStringObjects()
    {
        $arrayList = listOf(new StringableObject(2), new StringableObject(3.1), new StringableObject(true));
        $result = $arrayList->joinToString("; ");
        $this->assertEquals("2; 3.1; true", $result);
    }

    public function testShouldThrowExceptionWhenCollectToStringIsCalledOnListWithNotStringableObjects()
    {
        $arrayList = new ArrayList(new Element("0", "a"), new Element("1", "b"), new Element("2", "c"));
        $this->expectException(NotConvertibleToStringException::class);
        $arrayList->collectToString("; ");
    }
}



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
    public function toString(): string {
        return strval($this->value);
    }
    public function __toString(): string {
        return $this->toString();
    }
}
