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

    public static function isList($value): bool
    {
        return $value instanceof ListInterface;
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

function isList($value): bool
{
    return Functions::isList($value);
}
