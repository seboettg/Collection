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

namespace Seboettg\Collection\Common;

use Seboettg\Collection\Comparable\Comparable;

final class Functions
{

    final public static function strval($value): string
    {
        if (is_double($value)) {
            $str = \strval($value);
            if (strlen($str) === 1) {
                return sprintf("%1\$.1f",$value);
            }
            return $str;
        }
        if (is_bool($value)) {
            return $value ? "true" : "false";
        }
        return "$value";
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
