<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Exceptions;

use Matthewbdaly\SMS\Exceptions\ClientException;
use PHPUnit\Framework\TestCase;

final class ClientExceptionTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $ex = new ClientException();
        self::assertInstanceOf(ClientException::class, $ex);
    }

    public function testIsThrowable(): void
    {
        $ex = new ClientException();
        self::assertInstanceOf(\Throwable::class, $ex);
    }
}
