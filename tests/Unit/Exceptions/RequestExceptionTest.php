<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Exceptions;

use DejwCake\SmsClient\Exceptions\RequestException;
use PHPUnit\Framework\TestCase;
use Throwable;

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
        self::assertInstanceOf(Throwable::class, $ex);
    }
}
