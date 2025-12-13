<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Drivers;

use Aws\Sns\SnsClient;
use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Drivers\Aws;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use Matthewbdaly\SMS\Tests\Support\SpySnsClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Aws::class)]
final class AwsTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $sns = $this->createStub(SnsClient::class);

        $driver = new Aws([
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ], $sns);

        self::assertInstanceOf(Aws::class, $driver);
    }

    public function testImplementsInterface(): void
    {
        $sns = $this->createStub(SnsClient::class);

        $driver = new Aws([
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ], $sns);

        self::assertInstanceOf(Driver::class, $driver);
    }

    public function testThrowsExceptionIfMisconfigured(): void
    {
        $this->expectException(DriverNotConfiguredException::class);

        new Aws([]);
    }

    public function testReturnsDriverName(): void
    {
        $sns = $this->createStub(SnsClient::class);

        $driver = new Aws([
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ], $sns);

        self::assertSame('Aws', $driver->getDriver());
    }

    public function testReturnsDriverEndpoint(): void
    {
        $sns = $this->createStub(SnsClient::class);

        $driver = new Aws([
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ], $sns);

        self::assertSame('', $driver->getEndpoint());
    }

    public function testCanBeConstructedWithConfigOnly(): void
    {
        $driver = new Aws([
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ]);

        self::assertSame('Aws', $driver->getDriver());
    }

    public function testSendsTheRequest(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];

        $expectedArgs = [
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => $msg['from'],
                ],
            ],
            'SMSType' => 'Transactional',
            'Message' => $msg['content'],
            'PhoneNumber' => $msg['to'],
        ];

        $sns = new SpySnsClient();

        $driver = new Aws([
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ], $sns);

        self::assertTrue($driver->sendRequest($msg));
        self::assertSame(1, $sns->getPublishCalls());
        self::assertSame($expectedArgs, $sns->getLastPublishArgs());
    }
}
