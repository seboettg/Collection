<?php

namespace Seboettg\Collection\Lists;

use Seboettg\Collection\Comparable\Comparable;
use Stringable;

final class Functions
{

    public static final function strval($value): string
    {
        if (is_double($value)) {
            $str = \strval($value);
            if (strlen($str) == 1) {
                return sprintf("%1\$.1f",$value);
            }
            return \strval($value);
        }
        if (is_bool($value)) {
            return $value ? "true" : "false";
        }
        return "$value";
    }

    public static final function emptyList(): ListInterface
    {
        return new class() implements ListInterface {
            private $array = [];
            use ArrayListTrait;
        };
    }

    public static final function listOf(...$elements): ListInterface
    {
        return listFromArray($elements);
    }

    public static final function listFromArray($elements): ListInterface
    {
        $list = emptyList();
        $list->setArray(array_values($elements));
        return $list;
    }

    public static function isScalarOrStringable($object)
    {
        return is_scalar($object)
            || method_exists($object, "__toString");
    }

    public static function isComparable($object): bool
    {
        return $object instanceof Comparable;
    }
}

function strval($value): string
{
    return Functions::strval($value);
}

function array_unique(array $array): array
{
    return Functions::array_unique($array);
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

function isScalarOrStringable($object): bool
{
    return Functions::isScalarOrStringable($object);
}

function isComparable($object): bool
{
    return Functions::isComparable($object);
}