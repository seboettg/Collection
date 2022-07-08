<?php

namespace Seboettg\Collection\Lists;

use Seboettg\Collection\Comparable\Comparable;
use Stringable;

final class Functions
{

    final public static function strval($value): string
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

    final public static function isScalarOrStringable($object): bool
    {
        return is_scalar($object)
            || method_exists($object, "__toString");
    }

    final public static function isComparable($object): bool
    {
        return $object instanceof Comparable;
    }

    final public static function isStringable($object): bool
    {
        return is_scalar($object)
            || method_exists($object, "__toString");
    }

    final public static function in_array($needle, $array): bool
    {
        if (is_scalar($needle)) {
            return \in_array($needle, $array);
        }
        if (isComparable($needle)) {
            foreach ($array as $item) {
                if (!isComparable($item)) {
                    return false;
                }
                if ($needle->compareTo($item) === 0) {
                    return true;
                }
            }
        }
        if (isStringable($needle)) {
            foreach ($array as $item) {
                if (strcmp((string)$needle, (string) $item) === 0) {
                    return true;
                }
            }
        }
        return false;
    }
}

function strval($value): string
{
    return Functions::strval($value);
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

function isStringable($object): bool
{
    return Functions::isStringable($object);
}

function isComparable($object): bool
{
    return Functions::isComparable($object);
}

function in_array($needle, $array): bool
{
   return Functions::in_array($needle, $array);
}
