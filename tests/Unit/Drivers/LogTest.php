<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Drivers;

use DejwCake\SmsClient\Contracts\Driver;
use DejwCake\SmsClient\Drivers\Log;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(Log::class)]
final class LogTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $logger = $this->createStub(LoggerInterface::class);
        $driver = new Log($logger);
        self::assertInstanceOf(Log::class, $driver);
    }

    public function testImplementsInterface(): void
    {
        $logger = $this->createStub(LoggerInterface::class);
        $driver = new Log($logger);
        self::assertInstanceOf(Driver::class, $driver);
    }

    public function testReturnsTheDriverName(): void
    {
        $logger = $this->createStub(LoggerInterface::class);
        $driver = new Log($logger);
        self::assertSame('Log', $driver->getDriver());
    }

    public function testReturnsTheDriverEndpoint(): void
    {
        $logger = $this->createStub(LoggerInterface::class);
        $driver = new Log($logger);
        self::assertSame('', $driver->getEndpoint());
    }

    public function testSendsTheRequestAndLogs(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];

        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects(self::once())
            ->method('info')
            ->with('Message sent', $msg);

        $driver = new Log($logger);
        self::assertTrue($driver->sendRequest($msg));
    }
}
