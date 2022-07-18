<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Drivers;

use Matthewbdaly\SMS\Drivers\Log;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

final class LogSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger): void
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Log::class);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_returns_the_driver_name(): void
    {
        $this->getDriver()->shouldReturn('Log');
    }

    public function it_returns_the_driver_endpoint(): void
    {
        $this->getEndpoint()->shouldReturn('');
    }

    public function it_sends_the_request(LoggerInterface $logger): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $this->beConstructedWith($logger);
        $this->sendRequest($msg)->shouldReturn(true);
        $logger->info('Message sent', $msg)->shouldHaveBeenCalled();
    }
}
