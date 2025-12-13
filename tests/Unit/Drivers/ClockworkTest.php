<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Unit\Drivers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleInterface;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Drivers\Clockwork;
use Matthewbdaly\SMS\Exceptions\ClientException;
use Matthewbdaly\SMS\Exceptions\ConnectException;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use Matthewbdaly\SMS\Exceptions\RequestException;
use Matthewbdaly\SMS\Exceptions\ServerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(Clockwork::class)]
final class ClockworkTest extends TestCase
{
    private function makeDriver(
        GuzzleInterface $client,
        ResponseInterface $response,
        array $config = ['apiKey' => 'MY_DUMMY_API_KEY'],
    ): Clockwork {
        return new Clockwork($client, $response, $config);
    }

    public function testIsInitializable(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertInstanceOf(Clockwork::class, $driver);
    }

    public function testImplementsInterface(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertInstanceOf(Driver::class, $driver);
    }

    public function testThrowsExceptionIfMisconfigured(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $this->expectException(DriverNotConfiguredException::class);
        new Clockwork($client, $response, []);
    }

    public function testReturnsTheDriverName(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertSame('Clockwork', $driver->getDriver());
    }

    public function testReturnsTheDriverEndpoint(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertSame('https://api.clockworksms.com/http/send.aspx', $driver->getEndpoint());
    }

    public function testSendsTheRequest(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler([
            new GuzzleResponse(201),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        self::assertTrue($driver->sendRequest($msg));
    }

    public function testThrowsClientExceptionOn400(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler([
            new GuzzleClientException('', new Request('POST', 'test'), new GuzzleResponse()),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        $this->expectException(ClientException::class);
        $driver->sendRequest($msg);
    }

    public function testThrowsServerExceptionOn500(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler([
            new GuzzleServerException('', new Request('POST', 'test'), new GuzzleResponse()),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        $this->expectException(ServerException::class);
        $driver->sendRequest($msg);
    }

    public function testThrowsRequestException(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler([
            new GuzzleRequestException('', new Request('POST', 'test')),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        $this->expectException(RequestException::class);
        $driver->sendRequest($msg);
    }

    public function testThrowsConnectException(): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'content' => 'Just testing',
        ];
        $mock = new MockHandler([
            new GuzzleConnectException('', new Request('POST', 'test')),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $response = $this->createStub(ResponseInterface::class);
        $driver = $this->makeDriver($client, $response);
        $this->expectException(ConnectException::class);
        $driver->sendRequest($msg);
    }
}
