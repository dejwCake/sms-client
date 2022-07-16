<?php

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
use Matthewbdaly\SMS\Drivers\O2SK;
use Psr\Http\Message\ResponseInterface;
use Matthewbdaly\SMS\Drivers\Twilio;
use PhpSpec\ObjectBehavior;

class O2SKSpec extends ObjectBehavior
{
    public function let(GuzzleInterface $client)
    {
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
        ];
        $this->beConstructedWith($client, $config);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(O2SK::class);
    }

    public function it_implements_interface()
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_throws_exception_if_api_key_not_configured(GuzzleInterface $client)
    {
        $config = [
        ];
        $this->beConstructedWith($client, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$client, $config]
        );
    }

    public function it_returns_the_driver_name()
    {
        $this->getDriver()->shouldReturn('O2SK');
    }

    public function it_returns_the_driver_endpoint()
    {
        $this->getEndpoint()->shouldReturn(
            'https://api-tls12.smstools.sk/3/send_batch'
        );
    }

    public function it_sends_the_request()
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new GuzzleResponse(201),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
        ];
        $this->beConstructedWith($client, $config);
        $this->sendRequest($msg)->shouldReturn(true);
    }

    public function it_throws_an_error_for_400()
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new ClientException("", new Request('POST', 'test'), new GuzzleResponse())
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
        ];
        $this->beConstructedWith($client, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ClientException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_500()
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new ServerException("", new Request('POST', 'test'), new GuzzleResponse())
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
        ];
        $this->beConstructedWith($client, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ServerException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_request_exception()
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new RequestException("", new Request('POST', 'test'))
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
        ];
        $this->beConstructedWith($client, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\RequestException')->during('sendRequest', [$msg]);
    }

    public function it_throws_an_error_for_connect_exception()
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler(
            [
                new ConnectException("", new Request('POST', 'test'))
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
        ];
        $this->beConstructedWith($client, $config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\ConnectException')->during('sendRequest', [$msg]);
    }
}
