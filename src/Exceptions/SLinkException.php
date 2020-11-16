<?php

namespace Slink\Exceptions;

use Exception;
use Throwable;

class SLinkException extends Exception
{
    public function __construct(string $message = null, Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct($message, 500);
    }
}
