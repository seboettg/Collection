[![PHP](https://img.shields.io/badge/PHP-%3E=7.0-green.svg?style=flat)](http://docs.php.net/manual/en/migration70.new-features.php)
[![Total Downloads](https://poser.pugx.org/seboettg/collection/downloads)](https://packagist.org/packages/seboettg/collection) 
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://bitbucket.org/bibsonomy/restclient-php/raw/default/license.txt)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/seboettg/Collection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/seboettg/Collection/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/seboettg/Collection/badges/build.png?b=master)](https://scrutinizer-ci.com/g/seboettg/Collection/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/seboettg/Collection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/seboettg/Collection/code-structure/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/seboettg/Collection/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Collection

Collection is a set of useful wrapper classes for arrays, similar to Java Collection.

The current version comes with a ArrayList, a Stack, and a Queue. These classes can be used as wrapper for simple arrays. 
Using ArrayList, furthermore you can implement the Comparable interface for elements of the ArrayList and the abstract class Comparator 
to be able to sort the Elements in the ArrayList comfortable. 

## Installing Collection ##

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

## ArrayList ##

### Simple Usage ###

Initialize ArrayList
```php
<?php
use Seboettg\Collection\ArrayList;
$list = new ArrayList("a", "c", "h", "k", "j");
$map = new ArrayList();
$map->setArray([
    "c" => "cc",
    "b" => "bb",
    "a" => "aa"
]);
```

Get elements

```php

for ($i = 0; $i < $list->count(); ++$i) {
    echo $list->get($i)." ";
}
```
This will output:
```bash
a c h k j 
```

ArrayList implements the ArrayAccess interface, so you can also access elements in an instance of ArrayList in exactly the same way as you access an element in arrays:

```php

for ($i = 0; $i < $list->count(); ++$i) {
    echo $list[$i]." ";
}
```

Iterate ArrayList using foreach

```php
foreach ($map as $key => $value) {
    echo "[".$key."] => ".$value."\n";
}
```
Output:
```bash
c => cc
b => bb
a => aa
```

Set, add or append Elements

```php
//set element
$map->set("d", "dd");
//or
$map["d"] = "dd";
 
//add element
$map->add("d", "ddd")
print_r($map[$d]);
/*
output:
Array(
  0 => "dd",
  1 => "ddd"
)
*/

$list->append("z"); //append to the end of $list

```

remove, replace, clear

```php
$map->remove("d"); //removes d from $map

$list->replace(["z", "y", "x"]); //replaces all elements by the specified

$list->clear(); //removes all elements of the list
``` 


### Advanced Usage ###

#### Inherit from ArrayList ####
Inherit from ArrayList to extend your class with the whole functionality from ArrayList:
```php
<?php 
namespace Vendor\Project;
use Seboettg\Collection\ArrayList;
class MyCustomList extends ArrayList {
    
    protected $myCustomProperty;
    
    protected function myCustomFunction()
    {
        //...
    }
}

``` 
Or use the ArrayListTrait in case of that your custom class inherits already from another class
```php
<?php 
namespace Vendor\Project;
use Seboettg\Collection\ArrayListTrait;
class MyCustomList extends MyOtherCustomClass {
    
    use ArrayListTrait;
    
    protected $myCustomProperty;
    
    protected function myCustomFunction()
    {
        //...
    }
}
```


#### Sorting an ArrayList ####
Implement the Comparable interface 
```php
<?php
namespace Vendor\App\Model;
use Seboettg\Collection\Comparable;
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
    public function compareTo(Comparable $b)
    {
        return strcmp($this->attribute1, $b->getAttribute1());
    }
}
```

Create a comparator class 

```php
<?php
namespace Vendor\App\Util;

use Seboettg\Collection\Comparator;
use Seboettg\Collection\Comparable;

class Attribute1Comparator extends Comparator
{
    public function compare(Comparable $a, Comparable $b)
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
use Seboettg\Collection\ArrayList;
use Seboettg\Collection\Collections;
use Seboettg\Collection\Comparator;
use Vendor\App\Util\Attribute1Comparator;
use Vendor\App\Model\Element;


$list = new ArrayList(
    new Element("b","bar"),
    new Element("a","foo"),
    new Element("c","foobar")
);

Collections::sort($list, new Attribute1Comparator(Comparator::ORDER_ASC));

```

#### sort your list using a custom order ####


```php
<?php
use Seboettg\Collection\Comparator;
use Seboettg\Collection\Comparable;
use Seboettg\Collection\ArrayList;
use Seboettg\Collection\Collections;
use Vendor\App\Model\Element;

//Define a custom Comparator
class MyCustomOrderComparator extends Comparator
{
    public function compare(Comparable $a, Comparable $b)
    {
        return (array_search($a->getAttribute1(), $this->customOrder) >= array_search($b->getAttribute1(), $this->customOrder)) ? 1 : -1;
    }
}

$list = new ArrayList(
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

#### filter your list ####

```php
$list = new ArrayList(
    new Element("a", "aa"),
    new Element("b", "bb"),
    new Element("c", "cc"),
    new Element("k", "kk"),
    new Element("d", "dd"),
);

$newList = $list->filterByKeys([0, 2]); //returns new list containing 1st and 3rd element of $list
```

#### custom filter ####
```php
$list = new ArrayList(
    new Element("a", "aa"),
    new Element("b", "bb"),
    new Element("c", "cc"),
    new Element("k", "kk"),
    new Element("d", "dd"),
);

$arrayList = $list->filter(function (Element $elem) {
    return $elem->getAttribute2() === 'bb' || $elem->getAttribute2() === 'kk';
});

// $arrayList contains just the 2nd and the 4th element of $list
```

#### apply a function on each element ####

```php
$list = new ArrayList(1, 2, 3, 4, 5);
$cubicList = $list->map(function($i) {
    return $i * $i * $i;
});

// cubicList looks like this now: [1, 8, 27, 64, 125]
```

## Stack ##

A stack is a collection of elements, with two principal operations:

* push, which adds an element to the collection, and
* pop, which removes the most recently added element that was not yet removed.

An Stack is a LIFO data structure: last in, first out. 

### Examples ###

#### push, pop and peek ####
``` 
stack = new Stack();
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

``` 
echo $stack->search("c"); //outputs 0 due c does not exist anymore
echo $stack->search("a"); //outputs 2
echo $stack->search("b"); //outputs 1
```

## Queue ##
A queue is collection in which the elements are kept in order. A queue has two principle operations:

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

## Contribution ##
Fork this Repo and feel free to contribute your ideas using pull requests.
