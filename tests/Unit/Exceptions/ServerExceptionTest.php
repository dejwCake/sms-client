<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Exceptions;

use Matthewbdaly\SMS\Exceptions\ServerException;
use PHPUnit\Framework\TestCase;

final class ServerExceptionTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $ex = new ServerException();
        self::assertInstanceOf(ServerException::class, $ex);
    }

    public function testIsThrowable(): void
    {
        $ex = new ServerException();
        self::assertInstanceOf(\Throwable::class, $ex);
    }
}
