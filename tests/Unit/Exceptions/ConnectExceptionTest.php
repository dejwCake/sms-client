<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Exceptions;

use DejwCake\SmsClient\Exceptions\ConnectException;
use PHPUnit\Framework\TestCase;
use Throwable;

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
        self::assertInstanceOf(Throwable::class, $ex);
    }
}
