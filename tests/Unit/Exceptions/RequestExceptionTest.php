<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Exceptions;

use Matthewbdaly\SMS\Exceptions\RequestException;
use PHPUnit\Framework\TestCase;

final class RequestExceptionTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $ex = new RequestException();
        self::assertInstanceOf(RequestException::class, $ex);
    }

    public function testIsThrowable(): void
    {
        $ex = new RequestException();
        self::assertInstanceOf(\Throwable::class, $ex);
    }
}
