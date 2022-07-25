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
use Matthewbdaly\SMS\Drivers\Twilio;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;

final class TwilioSpec extends ObjectBehavior
{
    public function let(GuzzleInterface $client, ResponseInterface $response): void
    {
        $config = [
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Twilio::class);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_throws_exception_if_account_name_not_configured(
        GuzzleInterface $client,
        ResponseInterface $response,
    ): void {
        $config = [
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$client, $response, $config],
        );
    }

    public function it_throws_exception_if_api_token_not_configured(
        GuzzleInterface $client,
        ResponseInterface $response,
    ): void {
        $config = [
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$client, $response, $config],
        );
    }

    public function it_returns_the_driver_name(): void
    {
        $this->getDriver()->shouldReturn('Twilio');
    }

    public function it_returns_the_driver_endpoint(): void
    {
        $this->getEndpoint()->shouldReturn(
            'https://api.twilio.com/2010-04-01/Accounts/MY_TWILIO_ACCOUNT_ID/Messages.json',
        );
    }

    public function it_sends_the_request(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
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
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->sendRequest($msg)->shouldReturn(true);
    }

    public function it_throws_an_error_for_400(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
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
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ClientException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_500(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
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
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ServerException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_request_exception(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
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
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\RequestException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_connect_exception(ResponseInterface $response): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
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
            'accountId' => 'MY_TWILIO_ACCOUNT_ID',
            'apiToken' => 'MY_TWILIO_API_TOKEN',
        ];
        $this->beConstructedWith($client, $response, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ConnectException')->during('sendRequest', [$msg]);
    }
}
