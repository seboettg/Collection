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

namespace Seboettg\Collection\Assert;

use ReflectionFunction;
use Seboettg\Collection\Assert\Exception\NotApplicableCallableException;
use Seboettg\Collection\Assert\Exception\NotConvertibleToStringException;
use Seboettg\Collection\Assert\Exception\ObjectIsNotComparableException;
use Seboettg\Collection\Assert\Exception\TypeIsNotAScalarException;
use Seboettg\Collection\Assert\Exception\WrongTypeException;
use Seboettg\Collection\Comparable\Comparable;
use function is_scalar;
use function is_object;
use function method_exists;

final class Functions
{
    final public static function assertScalar($value, string $message): void
    {
        if (!is_scalar($value)) {
            throw new TypeIsNotAScalarException($message);
        }
    }

    final public static function assertType($value, string $fqcn, string $message)
    {
        if (!$value instanceof $fqcn) {
            throw new WrongTypeException($message);
        }
    }

    final public static function assertStringable($value, string $message)
    {
        if (!is_scalar($value)) {
            if (!self::isStrigableObject($value)) {
                throw new NotConvertibleToStringException($message);
            }
        }
    }

    private static function isStrigableObject($value): bool
    {
        return is_object($value) && method_exists($value, "__toString");
    }

    final public static function assertValidCallable(callable $callable, array $parameters)
    {
        $reflected = new ReflectionFunction($callable);
        if (count($reflected->getParameters()) !== count($parameters)) {
            throw new NotApplicableCallableException(
                "The number of parameters of the given callable does not match the expected number."
            );
        }
        for ($i = 0; $i < count($reflected->getParameters()); ++$i) {
            $reflectedParamType = $reflected->getParameters()[$i]->getType();
            $expectedParam = $parameters[$i];
            switch ($expectedParam) {
                case "scalar":
                    if (!in_array($reflectedParamType, ["int", "string", "bool", "float", null])) {
                        self::throwNotApplicableCallableException($i, "scalar", $reflectedParamType);
                    }
                    break;
                case "mixed":
                    //ignore, since every type is allowed
                    break;
                default:
                    if ($reflectedParamType->getName() !== $expectedParam) {
                        self::throwNotApplicableCallableException($i, $expectedParam, $reflectedParamType);
                    }
            }
        }
    }

    private static function throwNotApplicableCallableException($paramNumber, $expectedType, $actualType)
    {
        throw new NotApplicableCallableException(
            sprintf(
                "Parameter %d of type %s does not match the expected type of %s",
                $paramNumber,
                $actualType,
                $expectedType
            )
        );
    }

    /**
     * @param $object
     * @param string $message
     * @return void
     */
    final public static function assertComparable($object, string $message): void {
        if (is_scalar($object)) {
            return;
        }
        if (method_exists($object, '__toString')) {
            return;
        }
        if ($object instanceof Comparable) {
            return;
        }
        throw new ObjectIsNotComparableException($message);
    }
}

/**
 * @param $value
 * @param string $message description that will be included in the failure message if the assertion fails.
 * @return void
 */
function assertScalar($value, string $message): void
{
    Functions::assertScalar($value, $message);
}

/**
 * @param $value
 * @param string $fqcn full qualified class name
 * @param string $message description that will be included in the failure message if the assertion fails.
 * @return void
 */
function assertType($value, string $fqcn, string $message): void
{
    Functions::assertType($value, $fqcn, $message);
}

/**
 * @param $value
 * @param string $message description that will be included in the failure message if the assertion fails.
 * @return void
 */
function assertStringable($value, string $message): void
{
    Functions::assertStringable($value, $message);
}


function assertValidCallable(callable $callable, array $parameters)
{
    Functions::assertValidCallable($callable, $parameters);
}


function assertComparable($object, string $message): void
{
    Functions::assertComparable($object, $message);
}
