<?php
declare(strict_types=1);

namespace Seboettg\Collection\Assert\Exception;

use RuntimeException;
use Throwable;

abstract class AssertionException extends RuntimeException {
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
