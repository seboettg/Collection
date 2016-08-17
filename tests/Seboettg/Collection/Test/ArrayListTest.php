<?php
/*
 * This file is a part of HDS (HeBIS Discovery System). HDS is an 
 * extension of the open source library search engine VuFind, that 
 * allows users to search and browse beyond resources. More 
 * Information about VuFind you will find on http://www.vufind.org
 * 
 * Copyright (C) 2016 
 * HeBIS Verbundzentrale des HeBIS-Verbundes 
 * Goethe-Universität Frankfurt / Goethe University of Frankfurt
 * http://www.hebis.de
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace Seboettg\Collection\Test;


use Seboettg\Collection\ArrayList;
use Seboettg\Collection\Comparable;

class ArrayListTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ArrayList
     */
    private $numeratedArrayList;

    /**
     * @var ArrayList
     */
    private $hashMap;


    public function setUp()
    {
        $this->numeratedArrayList = new ArrayList([
            new Element("a", "aa"),
            new Element("b", "bb"),
            new Element("c", "cc"),
            new Element("k", "kk"),
            new Element("d", "dd"),
        ]);

        $this->hashMap = new ArrayList([
            "c" => new Element("c"),
            "a" => new Element("a"),
            "h" => new Element("h"),
        ]);
    }


    public function testAppend()
    {
        $i = $this->numeratedArrayList->count();
        $this->numeratedArrayList->append(new Element("3", "33"));
        $j = $this->numeratedArrayList->count();
        $this->assertEquals($i + 1, $j);
        $this->assertEquals("3", $this->numeratedArrayList->toArray()[$i]->getAttr1());
    }

    public function testSet()
    {
        $this->hashMap->set("c", new Element("ce"));
        $this->assertEquals("ce", $this->hashMap->toArray()['c']->getAttr1());
    }

    public function testCompareTo()
    {
        $arr = $this->hashMap->toArray();
        usort($arr, function (Comparable $a, Comparable $b) {
            return $a->compareTo($b);
        });

        $this->assertEquals("a", $arr[0]->getAttr1());
        $this->assertEquals("c", $arr[1]->getAttr1());
        $this->assertEquals("h", $arr[2]->getAttr1());
    }

    public function testReplace()
    {
        $this->hashMap->replace($this->numeratedArrayList->toArray());
        $keys = array_keys($this->hashMap->toArray());
        foreach ($keys as $key) {
            $this->assertInternalType("int", $key);
            $this->assertNotEmpty($this->hashMap->get($key));
        }
    }

    public function testClear()
    {
        $this->assertTrue($this->hashMap->count() > 0);
        $this->assertEquals(0, $this->hashMap->clear()->count());
    }

    public function testSetArray()
    {
        $this->hashMap->setArray($this->numeratedArrayList->toArray());
        $keys = array_keys($this->hashMap->toArray());
        foreach ($keys as $key) {
            $this->assertInternalType("int", $key);
            $this->assertNotEmpty($this->hashMap->get($key));
        }
    }

    public function testShuffle()
    {
        $arr = $this->numeratedArrayList->toArray();
        usort($arr, function (Comparable $a, Comparable $b) {
            return $a->compareTo($b);
        });
        $this->numeratedArrayList->replace($arr);
        for ($i = 0; $i < $this->numeratedArrayList->count() - 1; ++$i) {
            $lte = ($this->numeratedArrayList->get($i)->getAttr1() <= $this->numeratedArrayList->get($i + 1)->getAttr1());
            if (!$lte) {
                break;
            }
        }
        //each element on position $i is smaller than or equal to the element on position $i+1
        $this->assertTrue($lte);
        $arr1 = $this->numeratedArrayList->toArray();

        $this->numeratedArrayList->shuffle();

        $arr2 = $this->numeratedArrayList->toArray();

        // at least one element has another position as before
        for ($i = 0; $i < count($arr); ++$i) {
            $equal = ($arr1[$i]->getAttr1() == $arr2[$i]->getAttr1());
            if (!$equal) {
                break;
            }
        }
        $this->assertFalse($equal);
    }


    public function testHasKey()
    {
        $this->numeratedArrayList->hasKey(0);
        $this->hashMap->hasKey("c");

    }

    public function testHasValue()
    {
        $list = new ArrayList([
            "a",
            "b",
            "c"
        ]);

        $this->assertTrue($list->hasValue("a"));
    }

    public function testGetIterator()
    {
        $it = $this->numeratedArrayList->getIterator();

        foreach ($it as $key => $e) {
            $this->assertTrue(is_int($key));
            $this->assertInstanceOf("Seboettg\\Collection\\Test\\Element", $e);
        }
    }

    public function testRemove()
    {
        $list = new ArrayList([
            "a",
            "b",
            "c"
        ]);

        $list->append("d");
        $this->assertTrue($list->hasValue("d"));
        $list->remove(0);
        $this->assertFalse($list->hasValue("a"));
    }

    public function testOffsetGet()
    {
        $this->assertNotEmpty($this->numeratedArrayList[0]);
        $this->assertEmpty($this->numeratedArrayList[333]);
    }

    public function testOffsetSet()
    {
        $pos = $this->numeratedArrayList->count();
        $this->numeratedArrayList[$pos] = new Element($pos, $pos . $pos);
        $arr = $this->numeratedArrayList->toArray();
        $this->assertNotEmpty($arr[$pos]);
        $this->assertEquals($pos, $arr[$pos]->getAttr1());
    }

    public function testOffestExist()
    {
        $this->assertTrue(isset($this->hashMap['a']));
        $this->assertFalse(isset($this->numeratedArrayList[111]));
    }

    public function testOffsetUnset()
    {
        $list = new ArrayList(['a' => 'aa', 'b' => 'bb']);
        unset($list['a']);
        $this->assertFalse($list->hasKey('a'));
        $this->assertTrue($list->hasKey('b'));
    }

    public function testAdd()
    {
        $list = new ArrayList(['a' => 'aa', 'b' => 'bb', 'c' => 'cc']);
        $list->add('d', 'dd');
        $this->assertEquals('dd', $list->get('d'));
        $list->add('d', 'ddd');

        $dl = $list->get('d');
        $this->assertTrue(is_array($dl));
        $this->assertEquals('dd', $dl[0]);
        $this->assertEquals('ddd', $dl[1]);
    }

}


class Element implements Comparable
{

    private $attr1;

    private $attr2;

    public function __construct($attr1, $attr2 = "")
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
    public function setAttr1($attr1)
    {
        $this->attr1 = $attr1;
    }

    /**
     * @return mixed
     */
    public function getAttr2()
    {
        return $this->attr2;
    }

    /**
     * @param mixed $attr2
     */
    public function setAttr2($attr2)
    {
        $this->attr2 = $attr2;
    }

    /**
     * Compares this object with the specified object for order. Returns a negative integer, zero, or a positive
     * integer as this object is less than, equal to, or greater than the specified object.
     *
     * The implementor must ensure sgn(x.compareTo(y)) == -sgn(y.compareTo(x)) for all x and y.
     *
     * @param Comparable $b
     * @return int
     */
    public function compareTo(Comparable $b)
    {
        return strcmp($this->attr1, $b->getAttr1());
    }
}