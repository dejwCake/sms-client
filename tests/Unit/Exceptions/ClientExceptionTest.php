<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Exceptions;

use DejwCake\SmsClient\Exceptions\ClientException;
use PHPUnit\Framework\TestCase;
use Throwable;

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
        self::assertInstanceOf(Throwable::class, $ex);
    }
}
