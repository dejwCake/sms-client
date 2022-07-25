<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Matthewbdaly\SMS\Drivers\Nexmo;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

final class NexmoSpec extends ObjectBehavior
{
    public function let(GuzzleInterface $client, ResponseInterface $response): void
    {
        $config = [
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
        ];
        $this->beConstructedWith($client, $response, $config);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Nexmo::class);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_throws_exception_if_no_api_key(GuzzleInterface $client, ResponseInterface $response): void
    {
        $config = [
            'apiSecret' => 'bar',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$client, $response, $config],
        );
    }

    public function it_throws_exception_if_no_api_secret(GuzzleInterface $client, ResponseInterface $response): void
    {
        $config = [
            'apiKey' => 'foo',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$client, $response, $config],
        );
    }

    public function it_returns_the_driver_name(): void
    {
        $this->getDriver()->shouldReturn('Nexmo');
    }

    public function it_returns_the_driver_endpoint(): void
    {
        $this->getEndpoint()->shouldReturn('https://rest.nexmo.com/sms/json');
    }

    public function it_sends_the_request(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new GuzzleResponse(201),
            ],
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_DUMMY_API_KEY',
            'apiSecret' => 'MY_DUMMY_API_SECRET',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->sendRequest($msg)->shouldReturn(true);
    }

    public function it_throws_an_error_for_400(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new ClientException("", new Request('POST', 'test'), new GuzzleResponse()),
            ],
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_DUMMY_API_KEY',
            'apiSecret' => 'MY_DUMMY_API_SECRET',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ClientException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_500(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new ServerException("", new Request('POST', 'test'), new GuzzleResponse()),
            ],
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_DUMMY_API_KEY',
            'apiSecret' => 'MY_DUMMY_API_SECRET',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ServerException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_request_exception(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new RequestException("", new Request('POST', 'test')),
            ],
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_DUMMY_API_KEY',
            'apiSecret' => 'MY_DUMMY_API_SECRET',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\RequestException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_connect_exception(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new ConnectException("", new Request('POST', 'test')),
            ],
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_DUMMY_API_KEY',
            'apiSecret' => 'MY_DUMMY_API_SECRET',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ConnectException')->during('sendRequest', [$msg]);
    }
}
