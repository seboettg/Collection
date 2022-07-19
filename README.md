[![PHP](https://img.shields.io/badge/PHP-%3E=7.4-green.svg?style=flat)](http://docs.php.net/manual/en/migration74.new-features.php)
[![Total Downloads](https://poser.pugx.org/seboettg/collection/downloads)](https://packagist.org/packages/seboettg/collection/stats) 
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/seboettg/Collection/blob/master/LICENSE)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/seboettg/Collection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/seboettg/Collection/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/seboettg/Collection/badges/build.png?b=master)](https://scrutinizer-ci.com/g/seboettg/Collection/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/seboettg/Collection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/seboettg/Collection/code-structure/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/seboettg/Collection/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Collection

Collection is a set of useful wrapper classes for arrays, similar to Java's or Kotlin's collection packages. 

## Table of Contents
1. [Versions](#versions)
2. [Installing Collection](#install)
3. [Lists](#lists)
   1. [Getting started](#lists-getting-started)
   2. [Iterate over lists](#lists-iterate)
   3. [List operations](#lists-operations)
   4. [Map elements](#lists-map)
   5. [Filter elements](#lists-filter)
   6. [Logical operations](#lists-logical-operations)
   7. [forEach](#lists-foreach)
   8. [sorting](#lists-sorting)
4. [Maps](#maps)
   1. [Getting started](#maps-getting-started)
   2. [Access elements](#maps-access-elements)
   3. [Manipulate](#maps-manipulate)
   4. [Map elements](#maps-map)
   5. [Filter map entries](#maps-filter)
5. [Combining Lists and Maps](#lists-maps)
6. [Stack](#stack)
7. [Queue](#queue)
8. [Contribution](#contribution)


<a name="versions"/>

## Versions
Since [Version 4.0](https://github.com/seboettg/Collection/releases/tag/v4.0.0) you need PHP 7.4 or higher to use this library.
Since [Version 2.1](https://github.com/seboettg/Collection/releases/tag/v2.1.0) you need PHP 7.1 to use Collection library. Previous versions are running from PHP 5.6 upwards.


<a name="install"/>

## Installing Collection

The recommended way to install Collection is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of Collection:

```bash
php composer.phar require seboettg/collection
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

You can then later update Collection using composer:

 ```bash
composer.phar update
 ```

<a name="lists"/>

## Lists

List is an ordered collection with access to elements by indices – integer numbers that reflect their position. Elements
can occur more than once in a list. In other words: a list can contain any number of equal objects or occurrences of a 
single object. Two lists are considered equal if they have the same sizes and structurally equal elements at the same 
positions.

Lists are completely new implemented for version 4.0. The handling is much more oriented on a functional approach. 
Further more methods for associative arrays are moved to map.

<a name="lists-getting-started"/>

### Getting started

```php
use function Seboettg\Collection\Lists\listOf;
use function Seboettg\Collection\Lists\listFromArray;
//create a simple list
$list = listOf("a", "b", "c", "d");
print_r($list);
```
Output 
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => a
            [1] => b
            [2] => c
        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```
You also create a list from an existing array
```php
$array = ["d", "e", "f"];
$otherList = listFromArray($array);
```
Output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => d
            [1] => e
            [2] => f
        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```
As you may notice, this will reset the array keys

You can also create an empty List:
```php
use function Seboettg\Collection\Lists\emptyList;
$emptyList = emptyList();
echo $emptyList->count();
```
output
```
0
```

<a name="lists-iterate"/>

### Iterate over lists

```php
foreach ($list as $key => $value) {
    echo "[".$key."] => ".$value."\n";
}
```
Output:
```
[0] => a
[1] => b
[2] => c
```
or
```php
for ($i = 0; $i < $otherList->count(); ++$i) {
    echo $otherList->get($i) . " ";
}
```
output
```
d e f
```

<a name="lists-operations"/>

### List operations

You may add the elements of another list to a list by using `plus`:

```php
$newList = $list->plus($otherList);
print_r($newList);
```
output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => a
            [1] => b
            [2] => c
            [3] => d
            [4] => e
            [5] => f
        )
    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```
The same operation is applicable with arrays, with the same result:
```php
$newList = $list->plus($array);
```
You can also subtract the elements of another list or any `iterable` using `minus`:
```php
$subtract = $newList->minus($list);
print_r($subtract);
```
output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => d
            [1] => e
            [2] => f
        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```
To get the intersection of two lists (or an iterable), you can use the `intersect` method:
```php
$intersection = $newList->intersect(listOf("b", "d", "f", "h", "i"));
print_r($intersection);
```
output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => b
            [1] => d
            [2] => f
        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```
To get a list containing distinct elements, use `distinct`:
```php
$list = listOf("a", "b", "a", "d", "e", "e", "g")
print_r($list->distinct());
```
output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => a
            [1] => b
            [2] => d
            [3] => e
            [4] => g
        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```

<a name="lists-map"/>

### Map all elements of a list
If you need to modify all elements in a list, you can do it easily by using the `map` method:
```php
$list = listOf(1, 2, 3, 4, 5);
$cubicList = $list->map(fn ($i) => $i * $i * $i);
//result of $cubicList: 1, 8, 27, 64, 125
```

There is also a `mapNotNull` method that eliminates `null` values from the result:

```php
function divisibleByTwoOrNull(int $number): ?int {
    return $item % 2 === 0 ? $item : null;
}

listOf(0, 1, 2, 3, 4, 5)
    ->map(fn (int $number): ?int => divisibleByTwoOrNull($number));
//result: 0, 2, 4

```

<a name="lists-filter"/>

### Filter elements in a list

The `filter` method returns a list containing only elements matching the given predicate.

```php
$list = listOf("a", "b", "c", "d", "e", "f", "g", "h", "i", "j"):
$listOfCharactersThatAsciiNumbersIsOdd = $list
    ->filter(fn($letter) => ord($letter) % 2 !== 0);
//result of $listOfCharactersTharOrderNumbersAreOdd: "a", "c", "e", "g", "i"
```

<a name="lists-logical-operations"/>

### Logical operations

With the methods `any` and `all` you can check whether all elements (all) or at least one element (any) match a predicate.

```php
$list = listOf("a", "b", "c", "d", "e", "f", "g", "h", "i", "j"):
$list->all(fn($letter) => ord($letter) % 2 !== 0); // false
$list->any(fn($letter) => ord($letter) % 2 !== 0); // true

$list->all(fn($letter) => ord($letter) % 1 !== 0); // true
$list->any(fn($letter) => $letter === "z"); // false, since no character in the list is a 'z'
```

<a name="lists-foreach"/>

### forEach method

With the forEach method you can apply a closure or lambda functions on each element.

```php
$list = listOf("a", "b", "c");
$list->forEach(fn (string $item) => print($item . PHP_EOL));
```
output:
```
a
b
c
```

<a name="lists-sorting"/>

### Sorting a list
Implement the Comparable interface 
```php
<?php
namespace Vendor\App\Model;
use Seboettg\Collection\Comparable\Comparable;
class Element implements Comparable
{
    private $attribute1;
    private $attribute2;
    
    //contructor
    public function __construct($attribute1, $attribute2)
    {
        $this->attribute1 = $attribute1;
        $this->attribute2 = $attribute2;
    }
    
    // getter
    public function getAttribute1() { return $this->attribute1; }
    public function getAttribute2() { return $this->attribute2; }
    
    //compareTo function
    public function compareTo(Comparable $b): int
    {
        return strcmp($this->attribute1, $b->getAttribute1());
    }
}
```

Create a comparator class 

```php
<?php
namespace Vendor\App\Util;

use Seboettg\Collection\Comparable\Comparator;
use Seboettg\Collection\Comparable\Comparable;

class Attribute1Comparator extends Comparator
{
    public function compare(Comparable $a, Comparable $b): int
    {
        if ($this->sortingOrder === Comparator::ORDER_ASC) {
            return $a->compareTo($b);
        }
        return $b->compareTo($a);
    }
}
``` 

Sort your list

```php
<?php
use Seboettg\Collection\Lists;
use Seboettg\Collection\Collections;
use Seboettg\Collection\Comparable\Comparator;
use function Seboettg\Collection\Lists\listOf;
use Vendor\App\Util\Attribute1Comparator;
use Vendor\App\Model\Element;


$list = listOf(
    new Element("b","bar"),
    new Element("a","foo"),
    new Element("c","foobar")
);

Collections::sort($list, new Attribute1Comparator(Comparator::ORDER_ASC));

```

#### sort your list using a custom order ####

```php
<?php
use Seboettg\Collection\Comparable\Comparator;
use Seboettg\Collection\Comparable\Comparable;
use Seboettg\Collection\Lists;
use Seboettg\Collection\Collections;
use function Seboettg\Collection\Lists\listOf;
use Vendor\App\Model\Element;

//Define a custom Comparator
class MyCustomOrderComparator extends Comparator
{
    public function compare(Comparable $a, Comparable $b): int
    {
        return (array_search($a->getAttribute1(), $this->customOrder) >= array_search($b->getAttribute1(), $this->customOrder)) ? 1 : -1;
    }
}

$list = listOf(
    new Element("a", "aa"),
    new Element("b", "bb"),
    new Element("c", "cc"),
    new Element("k", "kk"),
    new Element("d", "dd"),
);

Collections::sort(
    $list, new MyCustomOrderComparator(Comparator::ORDER_CUSTOM, ["d", "k", "a", "b", "c"])
);

```

<a name="maps"/>

## Maps

A Map stores key-value pairs; keys are unique, but different keys can be paired with equal values. The Map interface 
provides specific methods, such as access to value by key, searching keys and values, and so on.

<a name="maps-getting-started"/>

### Getting started

A Map is a collection of keys that are paired with values. Therefore, to create a Map you need pairs first:

```php
use Seboettg\Collection\Map\Pair;
use function Seboettg\Collection\Map\pair;
use function Seboettg\Collection\Map\mapOf;

$pair1 = pair("Ceres", "Giuseppe Piazzi")

//or you use the factory, with the same result:
$pair2 = Pair::factory("Pallas", "Heinrich Wilhelm Olbers");

//Now you can add both pairs to a map
$map = mapOf($pair1, $pair2);
print_r($map);
```
output
```
Seboettg\Collection\Map\MapInterface@anonymous Object
(
    [array:Seboettg\Collection\Map\MapInterface@anonymous:private] => Array
        (
            [Ceres] => Giuseppe Piazzi
            [Pallas] => Heinrich Wilhelm Olbers
        )

)
```
You can also create an empty Map:
```php
use function Seboettg\Collection\Map\emptyMap;
$emptyMap = emptyMap();
echo $emptyMap->count();
```
output
```
0
```

### Access elements

```php
use function Seboettg\Collection\Map\mapOf;
$asteroidExplorerMap = mapOf(
    pair("Ceres", "Giuseppe Piazzi"),
    pair("Pallas", "Heinrich Wilhelm Olbers"),
    pair("Juno", "Karl Ludwig Harding"),
    pair("Vesta", "Heinrich Wilhelm Olbers")
);

$juno = $asteroidExplorerMap->get("Juno"); //Karl Ludwig Harding

// or access elements like an array
$pallas = $asteroidExplorerMap["Pallas"]; //Heinrich Wilhelm Olbers

//get a list of all keys
$asteroids = $asteroidExplorerMap->getKeys(); //Ceres, Pallas, Juno, Vesta

//get a list of all values
$explorer = $asteroidExplorerMap
    ->values()
    ->distinct(); // "Giuseppe Piazzi", "Heinrich Wilhelm Olbers", "Karl Ludwig Harding"

$explorer = $asteroidExplorerMap
    ->getOrElse("Iris", fn() => "unknown"); //$explorer = "unknown"

```
you are also able to get all map entries as a list of pairs
```php
$keyValuePairs = $asteroidExplorerMap->getEntries();
```
output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => Seboettg\Collection\Map\Pair Object
                (
                    [key:Seboettg\Collection\Map\Pair:private] => Ceres
                    [value:Seboettg\Collection\Map\Pair:private] => Giuseppe Piazzi
                )

            [1] => Seboettg\Collection\Map\Pair Object
                (
                    [key:Seboettg\Collection\Map\Pair:private] => Pallas
                    [value:Seboettg\Collection\Map\Pair:private] => Heinrich Wilhelm Olbers
                )

            [2] => Seboettg\Collection\Map\Pair Object
                (
                    [key:Seboettg\Collection\Map\Pair:private] => Juno
                    [value:Seboettg\Collection\Map\Pair:private] => Karl Ludwig Harding
                )

            [3] => Seboettg\Collection\Map\Pair Object
                (
                    [key:Seboettg\Collection\Map\Pair:private] => Vesta
                    [value:Seboettg\Collection\Map\Pair:private] => Heinrich Wilhelm Olbers
                )

        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```

<a name="maps-manipulate"/>

### Manipulate a map

```php
use function Seboettg\Collection\Map\emptyMap;

$map = emptyMap();

//put
$map->put("ABC", 1);
echo $map["ABC"]; // 1

//put via array assignment
$map["ABC"] = 2;
echo $map["ABC"]; // 2

//remove
$map->put("DEF", 3);
$map->remove("DEF");
echo $map->get("DEF"); // null
```

<a name="maps-map"/>

### Map elements

The signature of given transform function for mapping must have either a `Pair` parameter or a `key` and a `value` parameter.
The map function always returns a list of type `ListInterface`:

```php
use function Seboettg\Collection\Map\mapOf;

class Asteroid {
    public string $name;
    public ?string $explorer;
    public ?float $diameter;
    public function __construct(string $name, string $explorer, float $diameter = null)
    {
        $this->name = $name;
        $this->explorer = $explorer;
        $this->diameter = $diameter;
    }
}

$asteroids = $asteroidExplorerMap
    ->map(fn (Pair $pair): Asteroid => new Asteroid($pair->getKey(), $pair->getValue()));

print_r($asteroids);
```
output
```
Seboettg\Collection\Lists\ListInterface@anonymous Object
(
    [array:Seboettg\Collection\Lists\ListInterface@anonymous:private] => Array
        (
            [0] => Asteroid Object
                (
                    [name] => Ceres
                    [explorer] => Giuseppe Piazzi
                    [diameter] => 
                )

            [1] => Asteroid Object
                (
                    [name] => Pallas
                    [explorer] => Heinrich Wilhelm Olbers
                    [diameter] => 
                )

            [2] => Asteroid Object
                (
                    [name] => Juno
                    [explorer] => Karl Ludwig Harding
                    [diameter] => 
                )

            [3] => Asteroid Object
                (
                    [name] => Vesta
                    [explorer] => Heinrich Wilhelm Olbers
                    [diameter] => 
                )

        )

    [offset:Seboettg\Collection\Lists\ListInterface@anonymous:private] => 0
)
```
You get the same result with a key-value signature:
```php
$asteroids = $asteroidExplorerMap
    ->map(fn (string $key, string $value): Asteroid => new Asteroid($key, $value));
```

<a name="maps-filter"/>

### Filter entries of a map

You may filter for elements by this way:

```php
$asteroidExplorerMap->filter(fn (Pair $pair): bool => $pair->getKey() !== "Juno");
```
or by this way:
```php
$asteroidExplorerMap->filter(fn (string $key, string $value): bool => $key !== "Juno");
```

<a name="lists-maps"/>

## Combining Lists and Maps

There are a lot of opportunities to use lists and maps in real world scenarios with a lot of 
advantages e.g. less boilerplate code and better code readability. 

The following json file represents a customer file that we want to use for processing.

_customer.json_
```json
[
   {
       "id": "A001",
       "lastname": "Doe",
       "firstname": "John",
       "createDate": "2022-06-10 09:21:12"
   },
   {
       "id": "A002",
       "lastname": "Doe",
       "firstname": "Jane",
       "createDate": "2022-06-10 09:21:13"
   },
   {
       "id": "A004",
       "lastname": "Mustermann",
       "firstname": "Erika",
       "createDate": "2022-06-11 08:21:13"
   }
]
```
We would like to get a map that associates the customer id with respective objects of type `Customer` and we want to apply a filter
so that we get only customers with lastname Doe.

```php
use function Seboettg\Collection\Lists\listFromArray;

class Customer {
    public string $id;
    public string $lastname;
    public string $firstname;
    public DateTime $createDate;
    public function __construct(
        string $id,
        string $lastname,
        string $firstname,
        DateTime $createDate
    ) {
        $this->id = $id;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->createDate = $createDate;
    }
}

$customerList = listFromArray(json_decode(file_get_contents("customer.json"), true));
$customerMap = $customerList
    ->filter(fn (array $customerArray) => $customerArray["lastname"] === "Doe") // filter for lastname Doe
    ->map(fn (array $customerArray) => new Customer(
        $customerArray["id"],
        $customerArray["lastname"],
        $customerArray["firstname"],
        DateTime::createFromFormat("Y-m-d H:i:s", $customerArray["createDate"])
     )) // map array to customer object
    ->associateBy(fn(Customer $customer) => $customer->id); // link the id with the respective customer object
print_($customerMap);
```
output
```
Seboettg\Collection\Map\MapInterface@anonymous Object
(
    [array:Seboettg\Collection\Map\MapInterface@anonymous:private] => Array
        (
            [A001] => Customer Object
                (
                    [id] => A001
                    [lastname] => Doe
                    [firstname] => John
                    [createDate] => DateTime Object
                        (
                            [date] => 2022-06-10 09:21:12.000000
                            [timezone_type] => 3
                            [timezone] => UTC
                        )

                )
            [A002] => Customer Object
                (
                    [id] => A002
                    [lastname] => Doe
                    [firstname] => Jane
                    [createDate] => DateTime Object
                        (
                            [date] => 2022-06-10 09:21:13.000000
                            [timezone_type] => 3
                            [timezone] => UTC
                        )
                )
        )
)
```

Another example: Assuming we have a customer service with a `getCustomerById` method.
We have a list of IDs with which we want to request the service. 

```php
$listOfIds = listOf("A001", "A002", "A004");
$customerMap = $listOfIds
    ->associateWith(fn ($customerId) => $customerService->getById($customerId))
```
output
```
Seboettg\Collection\Map\MapInterface@anonymous Object
(
    [array:Seboettg\Collection\Map\MapInterface@anonymous:private] => Array
        (
            [A001] => Customer Object
                (
                    [id] => A001
                    [lastname] => Doe
                    [firstname] => John
                    [createDate] => DateTime Object
                        (
                            [date] => 2022-06-10 09:21:12.000000
                            [timezone_type] => 3
                            [timezone] => UTC
                        )

                )
            [A002] ...
            [A004] ...
        )
)
```
<a name="stack"/>

## Stack ##

A stack is a collection of elements, with two principal operations:

* push, which adds an element to the collection, and
* pop, which removes the most recently added element that was not yet removed.

An Stack is a LIFO data structure: last in, first out. 

### Examples ###

#### push, pop and peek ####
```php
$stack = new Stack();
$stack->push("a")
      ->push("b")
      ->push("c");
echo $stack->pop(); // outputs c
echo $stack->count(); // outputs 2

// peek returns the element at the top of this stack without removing it from the stack.
echo $stack->peek(); // outputs b
echo $stack->count(); // outputs 2
```

#### search ####
The search function returns the position where an element is on this stack. If the passed element occurs as an element 
in this stack, this method returns the distance from the top of the stack of the occurrence nearest the top of the 
stack; the topmost element on the stack is considered to be at distance 1. If the passed element does not occur in 
the stack, this method returns 0.

```php
echo $stack->search("c"); //outputs 0 since c does not exist anymore
echo $stack->search("a"); //outputs 2
echo $stack->search("b"); //outputs 1
```

<a name="queue"/>

## Queue ##
A queue is a collection in which the elements are kept in order. A queue has two principle operations:

* enqueue
* dequeue 

### Examples ###
```php
$queue = new Queue();
$queue->enqueue("d")
    ->enqueue("z")
    ->enqueue("b")
    ->enqueue("a");
    
echo $queue->dequeue(); // outputs d
echo $queue->dequeue(); // outputs z
echo $queue->dequeue(); // outputs b
echo $queue->count(); // outputs 1
```

<a name="contribution"/>

## Contribution ##
Fork this Repo and feel free to contribute your ideas using pull requests.
