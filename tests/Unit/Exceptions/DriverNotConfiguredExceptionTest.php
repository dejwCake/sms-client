<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Exceptions;

use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use PHPUnit\Framework\TestCase;

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
        self::assertInstanceOf(\Throwable::class, $ex);
    }
}
