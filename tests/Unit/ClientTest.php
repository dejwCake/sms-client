<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit;

use Matthewbdaly\SMS\Client;
use Matthewbdaly\SMS\Contracts\Client as ClientContract;
use Matthewbdaly\SMS\Contracts\Driver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Client::class)]
final class ClientTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $driver = $this->createStub(Driver::class);
        $client = new Client($driver);
        self::assertInstanceOf(Client::class, $client);
    }

    public function testImplementsInterface(): void
    {
        $driver = $this->createStub(Driver::class);
        $client = new Client($driver);
        self::assertInstanceOf(ClientContract::class, $client);
    }

    public function testReturnsTheDriverName(): void
    {
        $driver = $this->getMockBuilder(Driver::class)->getMock();
        $driver->expects(self::once())
            ->method('getDriver')
            ->willReturn('Test');

        $client = new Client($driver);
        self::assertSame('Test', $client->getDriver());
    }

    public function testSendsAMessage(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];

        $driver = $this->getMockBuilder(Driver::class)->getMock();
        $driver->expects(self::once())
            ->method('sendRequest')
            ->with($msg)
            ->willReturn(true);

        $client = new Client($driver);
        self::assertTrue($client->send($msg));
    }
}
