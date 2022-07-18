<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS;

use Matthewbdaly\SMS\Client;
use Matthewbdaly\SMS\Contracts\Driver;
use PhpSpec\ObjectBehavior;

final class ClientSpec extends ObjectBehavior
{
    public function let(Driver $driver): void
    {
        $this->beConstructedWith($driver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Client');
    }

    public function it_returns_the_driver_name(Driver $driver): void
    {
        $driver->getDriver()->willReturn('Test');
        $this->getDriver()->shouldReturn('Test');
    }

    public function it_sends_a_message(Driver $driver): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $driver->sendRequest($msg)->willReturn(true);
        $this->send($msg)->shouldReturn(true);
    }
}
