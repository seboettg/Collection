<?php

namespace Seboettg\Collection\Lists;

use Seboettg\Collection\Comparable\Comparable;

final class Functions
{
    final public static function emptyList(): ListInterface
    {
        return new class() implements ListInterface {
            private $array = [];
            use ArrayListTrait;
        };
    }

    final public static function listOf(...$elements): ListInterface
    {
        return listFromArray($elements);
    }

    final public static function listFromArray($elements): ListInterface
    {
        $list = emptyList();
        $list->setArray(array_values($elements));
        return $list;
    }
}

function emptyList(): ListInterface
{
    return Functions::emptyList();
}

function listOf(...$elements): ListInterface
{
    return Functions::listOf(...$elements);
}

function listFromArray(array $elements): ListInterface
{
    return Functions::listFromArray($elements);
}
