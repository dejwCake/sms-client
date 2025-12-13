<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Tests\Unit\Drivers;

use DejwCake\SmsClient\Contracts\Driver;
use DejwCake\SmsClient\Contracts\Mailer;
use DejwCake\SmsClient\Drivers\Mail;
use DejwCake\SmsClient\Exceptions\DriverNotConfiguredException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Mail::class)]
final class MailTest extends TestCase
{
    public function testIsInitializable(): void
    {
        $mailer = $this->createStub(Mailer::class);
        $driver = new Mail($mailer, ['domain' => 'my.sms-gateway.com']);
        self::assertInstanceOf(Mail::class, $driver);
    }

    public function testImplementsInterface(): void
    {
        $mailer = $this->createStub(Mailer::class);
        $driver = new Mail($mailer, ['domain' => 'my.sms-gateway.com']);
        self::assertInstanceOf(Driver::class, $driver);
    }

    public function testThrowsExceptionIfMisconfigured(): void
    {
        $mailer = $this->createStub(Mailer::class);
        $this->expectException(DriverNotConfiguredException::class);
        new Mail($mailer, []);
    }

    public function testReturnsTheDriverName(): void
    {
        $mailer = $this->createStub(Mailer::class);
        $driver = new Mail($mailer, ['domain' => 'my.sms-gateway.com']);
        self::assertSame('Mail', $driver->getDriver());
    }

    public function testReturnsTheDriverEndpoint(): void
    {
        $mailer = $this->createStub(Mailer::class);
        $driver = new Mail($mailer, ['domain' => 'my.sms-gateway.com']);
        self::assertSame('my.sms-gateway.com', $driver->getEndpoint());
    }

    public function testSendsTheRequest(): void
    {
        $mailer = $this->getMockBuilder(Mailer::class)->getMock();

        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $config = [
            'domain' => 'my.sms-gateway.com',
        ];

        // Expect spaces removed and domain appended to recipient
        $expectedRecipient = '+4401234567890@my.sms-gateway.com';

        $mailer->expects(self::once())
            ->method('send')
            ->with($expectedRecipient, 'Just testing');

        $driver = new Mail($mailer, $config);
        self::assertTrue($driver->sendRequest($msg));
    }
}
