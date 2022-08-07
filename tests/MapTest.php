<?php
declare(strict_types=1);
/*
 * Copyright (C) 2022 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */
namespace Seboettg\Collection\Test;

use ReflectionClass;
use ReflectionException;
use Seboettg\Collection\Assert\Exception\NotApplicableCallableException;
use Seboettg\Collection\Assert\Exception\TypeIsNotAScalarException;
use PHPUnit\Framework\TestCase;
use Seboettg\Collection\Lists\ListInterface;
use Seboettg\Collection\Map\MapInterface;
use Seboettg\Collection\Map\MapTrait;
use Seboettg\Collection\Map\Pair;
use Seboettg\Collection\Test\Common\ComparableObject;
use Seboettg\Collection\Test\Common\StringableObject;
use Seboettg\Collection\Test\Common\Element;
use stdClass;
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Map\mapOf;
use function Seboettg\Collection\Map\pair;
use function Seboettg\Collection\Map\emptyMap;

class MapTest extends TestCase
{

    public function testGetEntriesShouldReturnAListOfPairs()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->assertEquals(
            listOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"])),
            $map->getEntries()
        );
    }

    public function testGetKeysShouldReturnAListOfKeysOfTypeScalar()
    {
        $keys = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]))->getKeys();
        $keys->forEach(fn($key) => $this->assertIsScalar($key));
        $this->assertEquals(
            listOf("a", "b", "c"),
            $keys
        );
    }

    public function testValuesShouldReturnAListOfValues()
    {
        $values = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]))->values();
        $values->forEach(fn($value) => $this->assertIsArray($value));
        $this->assertEquals(
            listOf(["a"], ["b"], ["c"]),
            $values
        );
    }

    public function testCountShouldReturnTheNumberOfElementsInMap()
    {
        $this->assertEquals(
            3,
            mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]))->count()
        );
    }

    public function testSizeShouldReturnSameAsCount()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->assertEquals(
            $map->count(),
            $map->size()
        );
    }

    public function testContainsShouldReturnTrueIfKeyExistsInMap()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->assertTrue($map->contains("a"));
    }

    public function testContainsShouldReturnFalseIfKeyDoesNotExistInMap()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->assertFalse($map->contains("d"));
    }

    public function testContainsShouldThrowExceptionIfGivenKeyIsNotAScalar()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->expectException(TypeIsNotAScalarException::class);
        $map->contains(["any"]);

    }

    public function testContainsKeyShouldBehaveAsContainsWhenKeyIsNotAScalar()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->expectException(TypeIsNotAScalarException::class);
        $map->contains(new stdClass());
    }

    public function testContainsKeyShouldBehaveAsContains()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->assertTrue($map->containsKey("a"));

        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->assertFalse($map->containsKey("d"));
    }

    public function testIsEmptyShouldReturnTrueWhenMapIsEmpty()
    {
        $this->assertTrue(emptyMap()->isEmpty());
    }

    public function testIsEmptyShouldReturnFalseWhenMapIsNotEmpty()
    {
        $this->assertFalse(mapOf(pair("a", ["a"]))->isEmpty());
    }

    public function testGetShouldReturnAssociatedValueOfKey()
    {
        $this->assertEquals(
            ["a"],
            mapOf(pair("a", ["a"]), pair("b", ["b"]))->get("a")
        );
    }

    public function testGetShouldReturnNullIfKeyDoesNotExist()
    {
        $this->assertEquals(
            null,
            mapOf(pair("a", ["a"]), pair("b", ["b"]))->get("c")
        );
    }

    public function testPutShouldAddAnotherKeyValueAssociation()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $map->put("d", ["d"]);
        $this->assertEquals(4, $map->count());
        $this->assertTrue($map->contains("d"));
        $this->assertEquals(["d"], $map->get("d"));
    }

    public function testPutShouldThrowExceptionIfKeyNotAScalar()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $this->expectException(TypeIsNotAScalarException::class);
        $map->put(["d"], "d");
    }

    public function testPutShouldAllowNullValues()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $map->put("d", null);
        $this->assertEquals(null, $map->get("d"));
    }

    public function testPutAllShouldAddEntriesOfTheGivenMap()
    {
        $map1 = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $map2 = mapOf(pair("d", ["d"]), pair("e", ["e"]));
        $map1->putAll($map2);
        $this->assertEquals(
            5,
            $map1->count()
        );
        for ($i = 97; $i < 102; ++$i) {
            $this->assertEquals([chr($i)], $map1->get(chr($i)));
        }

        $map = emptyMap();
        $map->putAll(listOf(pair("a", ["a"]), pair("b", ["b"]))->toMap());
        $this->assertEquals(2, $map->count());
        $this->assertEquals(
            mapOf(pair("a", ["a"]), pair("b", ["b"])),
            $map
        );
    }

    public function testRemoveShouldRemoveAnEntryFromMap()
    {
        $map = mapOf(pair("a", ["a"]), pair("b", ["b"]), pair("c", ["c"]));
        $map->remove("c");

        $this->assertEquals(
            mapOf(pair("a", ["a"]), pair("b", ["b"])),
            $map
        );
    }

    public function testAllShouldReturnTrueIfAllEntriesMatchTheGivenPredicateOtherwiseFalse()
    {
        $this->assertTrue(
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3))->all(fn(Pair $pair): bool => $pair->getValue() > 0)
        );

        $this->assertFalse(
            mapOf(pair("a", -1), pair("b", 0), pair("c", 1))->all(fn(Pair $pair): bool => $pair->getValue() > 0)
        );
    }

    public function testAnyShouldReturnTrueIfAnyOfTheEntriesMatchTheGivenPredicateOtherwiseFalse()
    {
        $this->assertTrue(
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3))->any(fn(Pair $pair): bool => $pair->getValue() > 0)
        );

        $this->assertTrue(
            mapOf(pair("a", -1), pair("b", 0), pair("c", 1))->any(fn(Pair $pair): bool => $pair->getValue() > 0)
        );

        $this->assertFalse(
            mapOf(pair("a", -2), pair("b", -1), pair("c", 0))->any(fn(Pair $pair): bool => $pair->getValue() > 0)
        );
    }

    public function testFilterShouldHandleCallableWithPairParameter()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3));
        $this->assertEquals(3, $map->count());

        $this->assertEquals(
            1,
            $map->filter(fn(Pair $pair): bool => $pair->getKey() === "c")
                ->count()
        );
    }

    public function testFilterShouldHandleCallableWithKeyValueParameters()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3));
        $this->assertEquals(3, $map->count());

        $this->assertEquals(
            1,
            $map->filter(fn($key, $_): bool => $key === "c")
                ->count()
        );
    }

    public function testFilterShouldHandleCallableWithTypedKeyValueParameters()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3));
        $this->assertEquals(3, $map->count());

        $this->assertEquals(
            1,
            $map->filter(fn(string $key, $_): bool => $key === "c")
                ->count()
        );
    }

    public function testFilterShouldThrowNotApplicableCallableExceptionIfCallableInvalid()
    {
        $this->expectException(NotApplicableCallableException::class);
        $this->expectExceptionMessage("Parameter 0 of type stdClass does not match the expected type of scalar");
        $map = mapOf(pair(0, "a"), pair(1, "b"));
        //stdClass not allowed as key
        $map->filter(fn(stdClass $x, string $y) => $y === "b");
    }

    public function testFilterShouldRemoveNullEntriesIfCallableNull()
    {
        $map = emptyMap();
        $map->setArray([1 => "a", 2 => "b", 3 => "c", 4 => null, 5 => "e", 6 => null]);
        $this->assertEquals(
            mapOf(pair(1, "a"), pair(2, "b"), pair(3, "c"), pair(5, "e")),
            $map->filter()
        );
    }

    public function testGetOrElseShouldReturnValueIfKeyExists()
    {
        $this->assertEquals(
            1,
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3))->getOrElse("a", fn() => null)
        );
    }

    public function testGetOrElseShouldReturnResultOfDefaultValueCallable()
    {
        $this->assertEquals(
            2,
            mapOf(pair("b", 2), pair("c", 3))->getOrElse("a", fn($self) => $self["b"])
        );
    }

    public function testMapShouldApplyMappingCallableForEachEntryAndReturnResultAsList()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3));

        $result = $map->map(fn(Pair $pair) => new TestObject($pair->getKey(), $pair->getValue()));
        $this->assertInstanceOf(ListInterface::class, $result);
        $this->assertCount(3, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TestObject::class, $item);
        }
    }

    public function testMapNotNullShouldApplyMappingCallableForEachEntryAndReturnResultAsListWithoutNullValues()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3));
        $result = $map->mapNotNull(
            fn(Pair $pair) => $pair->getKey() !== "b" ? new TestObject($pair->getKey(), $pair->getValue()) : null
        );
        $this->assertInstanceOf(ListInterface::class, $result);
        $this->assertCount(2, $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TestObject::class, $item);
        }
    }

    public function testMinusShouldRemoveEntriesOfTheGivenArrayOfKeys()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5));
        $result = $map->minus(["a", "d"]);
        $this->assertEquals(3, $result->count());
        $this->assertEquals(
            mapOf(pair("b", 2), pair("c", 3), pair("e", 5)),
            $result
        );
    }

    public function testMinusShouldRemoveEntriesOfTheGivenListOfKeys()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5));
        $result = $map->minus(listOf("b", "c"));
        $this->assertEquals(3, $result->count());
        $this->assertEquals(
            mapOf(pair("a", 1), pair("d", 4), pair("e", 5)),
            $result
        );
    }

    public function testPlusShouldAddEntriesFromTheGivenListOfPairs()
    {
        $map = mapOf(pair("a", 1), pair("b", 2));
        $result = $map->plus(listOf(pair("c", 3), pair("d", 4), pair("e", 5)));
        $this->assertEquals(5, $result->count());
        $this->assertEquals(
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5)),
            $result
        );
    }

    public function testPlusShouldAddEntriesFromTheGivenMap()
    {
        $map = mapOf(pair("a", 1), pair("b", 2));
        $result = $map->plus(mapOf(pair("c", 3), pair("d", 4), pair("e", 5)));
        $this->assertEquals(5, $result->count());
        $this->assertEquals(
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5)),
            $result
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testForEachShouldExecuteCallableForEachElement()
    {
        $map = mapOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5));
        $class = new class() {
            public int $i = 0;
            public array $array = [];
            public function increase() { $this->i++; }
            public function reset() { $this->i = 0; $this->array = []; }
        };

        $map->forEach(function (Pair $item) use ($class) {
            $class->increase();
            $class->array[$item->getKey()] = $item->getValue();
        });

        //ensure that increase was called as much as elements are in the map
        $this->assertTrue($map->count() === $class->i);

        $reflectedMap = new ReflectionClass($map);
        $arrayOfMap = $reflectedMap->getProperty("array");
        $arrayOfMap->setAccessible(true);
        $this->assertEquals($arrayOfMap->getValue($map), $class->array);

        $class->reset();

        $map->forEach(function (string $key, int $value) use ($class) {
            $class->increase();
            $class->array[$key] = $value;
        });
        //ensure that increase was called as much as elements are in the map
        $this->assertTrue($map->count() === $class->i);

        $reflectedMap = new ReflectionClass($map);
        $arrayOfMap = $reflectedMap->getProperty("array");
        $arrayOfMap->setAccessible(true);
        $this->assertEquals($arrayOfMap->getValue($map), $class->array);
    }

    public function testToListShouldReturnAListContainingEachEntryAsPair()
    {
        $this->assertEquals(
            listOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5)),
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3), pair("d", 4), pair("e", 5))->toList()
        );
    }

    public function testToMapShouldReturnANewMapContainingAllEntries() {
        $myMap = new class() implements MapInterface {
            private string $type = "myMap";
            private $array = [
                "a" => 1,
                "b" => 2,
                "c" => 3
            ];
            use MapTrait;
        };

        $newMap = $myMap->toMap();
        $this->assertNotEquals($newMap, $myMap);
        $this->assertEquals($newMap->getKeys(), $myMap->getKeys());
        $this->assertEquals($newMap->values(), $myMap->values());
    }

    public function testUnsetOnMap()
    {
        $map = mapOf(pair(1, "a"), pair(2, "b"), pair(3, "c"));
        unset($map[2]);
        $this->assertEquals(
            mapOf(pair(1, "a"), pair(3, "c")),
            $map
        );
    }

    public function test_toArray_shouldReturnAArray()
    {
        $this->assertIsArray(
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3))->toArray()
        );
        $this->assertEquals(
            ["a" => 1, "b" => 2, "c" => 3],
            mapOf(pair("a", 1), pair("b", 2), pair("c", 3))->toArray()
        );
    }

    public function test_setArray()
    {
        $map = mapOf(pair("a", 1), pair("b", 2));
        $map->setArray(["b" => 2]);
        $this->assertEquals(
            2,
            $map["b"]
        );
    }

    public function test_containsValue_shouldWorkWithScalars()
    {
        $map = mapOf(pair("a", 1), pair("b", 2));
        $this->assertTrue($map->containsValue(2));
        $this->assertFalse($map->containsValue(3));
    }

    public function test_containsValue_shouldWorkWithStringable()
    {
        $map = mapOf(pair(1, new StringableObject("a")), pair(2, new StringableObject("b")));
        $this->assertTrue($map->containsValue(new StringableObject("b")));
        $this->assertFalse($map->containsValue(new StringableObject("c")));
    }

    public function test_containsValue_shouldWorkWithComparable()
    {
        $map = mapOf(pair(1, new ComparableObject("a")), pair(2, new ComparableObject("b")));
        $this->assertTrue($map->containsValue(new ComparableObject("b")));
        $this->assertFalse($map->containsValue(new ComparableObject("c")));
    }

    public function test_containsValue_shouldWorkWithAnyType()
    {
        $map = mapOf(pair(1, new Element("a", "aa")), pair(2, new Element("b", "bb")));
        $this->assertTrue($map->containsValue(new Element("b", "bb")));
        $this->assertFalse($map->containsValue(new Element("b", "bc")));
    }

    public function testClear()
    {
        $map = mapOf(pair(1, "a"), pair(2, "b"));
        $this->assertCount(2, $map);
        $map->clear();
        $this->assertEquals(
            emptyMap(),
            $map
        );
    }

    public function test_isNotEmpty_true()
    {
        $this->assertTrue(listOf(pair("a", 1))->isNotEmpty());
    }

    public function test_isNotEmpty_false()
    {
        $this->assertFalse(emptyMap()->isNotEmpty());
    }
}
