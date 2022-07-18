<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Drivers;

use Matthewbdaly\SMS\Contracts\Mailer;
use Matthewbdaly\SMS\Drivers\Mail;
use PhpSpec\ObjectBehavior;

final class MailSpec extends ObjectBehavior
{
    public function let(Mailer $mailer): void
    {
        $config = [
            'domain' => 'my.sms-gateway.com',
        ];
        $this->beConstructedWith($mailer, $config);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Mail::class);
    }

    public function it_throws_exception_if_misconfigured(Mailer $mailer): void
    {
        $config = [
        ];
        $this->beConstructedWith($mailer, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$mailer, $config],
        );
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_returns_the_driver_name(): void
    {
        $this->getDriver()->shouldReturn('Mail');
    }

    /** @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter */
    public function it_returns_the_driver_endpoint(Mailer $mailer): void
    {
        $this->getEndpoint()->shouldReturn('my.sms-gateway.com');
    }

    public function it_sends_the_request(Mailer $mailer): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $config = [
            'domain' => 'my.sms-gateway.com',
        ];
        $mailer->send('+4401234567890@my.sms-gateway.com', 'Just testing')->shouldBeCalled();
        $this->beConstructedWith($mailer, $config);
        $this->sendRequest($msg)->shouldReturn(true);
    }
}
