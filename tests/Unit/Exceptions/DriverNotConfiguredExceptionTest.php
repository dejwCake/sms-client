<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Exceptions;

use DejwCake\SmsClient\Exceptions\DriverNotConfiguredException;
use PHPUnit\Framework\TestCase;
use Throwable;

final class DriverNotConfiguredExceptionTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $ex = new DriverNotConfiguredException();
        self::assertInstanceOf(DriverNotConfiguredException::class, $ex);
    }

    public function testIsThrowable(): void
    {
        $ex = new DriverNotConfiguredException();
        self::assertInstanceOf(Throwable::class, $ex);
    }
}
