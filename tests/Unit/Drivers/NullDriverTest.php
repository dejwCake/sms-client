<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Drivers;

use DejwCake\SmsClient\Contracts\Driver;
use DejwCake\SmsClient\Drivers\NullDriver;
use GuzzleHttp\ClientInterface as GuzzleInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(NullDriver::class)]
final class NullDriverTest extends TestCase
{
    private function makeDriver(GuzzleInterface $client, ResponseInterface $response): NullDriver
    {
        return new NullDriver($client, $response);
    }

    public function testIsInitializable(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertInstanceOf(NullDriver::class, $driver);
    }

    public function testImplementsInterface(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertInstanceOf(Driver::class, $driver);
    }

    public function testReturnsTheDriverName(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertSame('Null', $driver->getDriver());
    }

    public function testReturnsTheDriverEndpoint(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertSame('', $driver->getEndpoint());
    }

    public function testSendsTheRequest(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];

        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);

        self::assertTrue($driver->sendRequest($msg));
    }
}
