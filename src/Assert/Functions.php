<?php
declare(strict_types=1);

namespace Seboettg\Collection\Assert;

use Seboettg\Collection\Assert\Exception\NotConvertibleToStringException;
use Seboettg\Collection\Assert\Exception\TypeIsNotAScalarException;
use Seboettg\Collection\Assert\Exception\WrongTypeException;
use function is_scalar;
use function is_object;
use function method_exists;

final class Functions
{
    public static final function assertScalar($value, string $message): void
    {
        if (!is_scalar($value)) {
            throw new TypeIsNotAScalarException($message);
        }
    }

    public static final function assertType($value, string $fqcn, string $message)
    {
        if (!$value instanceof $fqcn) {
            throw new WrongTypeException($message);
        }
    }

    public static final function assertStringable($value, string $message)
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
