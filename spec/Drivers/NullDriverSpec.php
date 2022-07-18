<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Drivers;

use GuzzleHttp\ClientInterface as GuzzleInterface;
use Matthewbdaly\SMS\Drivers\NullDriver;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

final class NullDriverSpec extends ObjectBehavior
{
    public function let(GuzzleInterface $client, ResponseInterface $response): void
    {
        $this->beConstructedWith($client, $response);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(NullDriver::class);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_returns_the_driver_name(): void
    {
        $this->getDriver()->shouldReturn('Null');
    }

    public function it_returns_the_driver_endpoint(): void
    {
        $this->getEndpoint()->shouldReturn('');
    }

    public function it_sends_the_request(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $this->sendRequest($msg)->shouldReturn(true);
    }
}
