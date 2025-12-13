<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Exceptions;

use Matthewbdaly\SMS\Exceptions\ConnectException;
use PHPUnit\Framework\TestCase;

final class ConnectExceptionTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $ex = new ConnectException();
        self::assertInstanceOf(ConnectException::class, $ex);
    }

    public function testIsThrowable(): void
    {
        $ex = new ConnectException();
        self::assertInstanceOf(\Throwable::class, $ex);
    }
}
