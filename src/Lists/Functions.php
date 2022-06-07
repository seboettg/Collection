<?php

namespace Seboettg\Collection\Lists;

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
        $list = emptyList();
        $list->setArray(array_values($elements));
        return $list;
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

function tuple($first, $second): Tuple
{
    return new Tuple($first, $second);
}
