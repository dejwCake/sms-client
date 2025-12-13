<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Exceptions;

use DejwCake\SmsClient\Exceptions\ServerException;
use PHPUnit\Framework\TestCase;
use Throwable;

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
        self::assertInstanceOf(Throwable::class, $ex);
    }
}
