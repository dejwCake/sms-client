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
use Matthewbdaly\SMS\Drivers\O2SK;
use Matthewbdaly\SMS\Exceptions\ClientException;
use Matthewbdaly\SMS\Exceptions\ConnectException;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use Matthewbdaly\SMS\Exceptions\RequestException;
use Matthewbdaly\SMS\Exceptions\ServerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(O2SK::class)]
final class O2SKTest extends TestCase
{
    private function makeDriver(GuzzleInterface $client, array $config = ['apiKey' => 'MY_O2SK_API_KEY',]): O2SK
    {
        return new O2SK($client, $config);
    }

    public function testIsInitializable(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $driver = $this->makeDriver($client);
        self::assertInstanceOf(O2SK::class, $driver);
    }

    public function testImplementsInterface(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $driver = $this->makeDriver($client);
        self::assertInstanceOf(Driver::class, $driver);
    }

    public function testThrowsExceptionIfApiKeyMissing(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $this->expectException(DriverNotConfiguredException::class);
        new O2SK($client, []);
    }

    public function testReturnsTheDriverName(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $driver = $this->makeDriver($client);
        self::assertSame('O2SK', $driver->getDriver());
    }

    public function testReturnsTheDriverEndpoint(): void
    {
        $client = $this->createStub(GuzzleInterface::class);
        $driver = $this->makeDriver($client);
        self::assertSame('https://api-tls12.smstools.sk/3/send_batch', $driver->getEndpoint());
    }

    public function testSendsTheRequest(): void
    {
        $msg = [
            'message' => 'Just testing',
            'sender' => ['text' => 'Tester'],
            'recipients' => [
                ['phonenr' => '+421911000000'],
            ],
        ];

        $mock = new MockHandler([
            new GuzzleResponse(201),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $driver = $this->makeDriver($client);

        self::assertTrue($driver->sendRequest($msg));
    }

    public function testThrowsClientExceptionOn400(): void
    {
        $msg = [
            'message' => 'Just testing',
            'sender' => ['text' => 'Tester'],
            'recipients' => [
                ['phonenr' => '+421911000000'],
            ],
        ];

        $mock = new MockHandler([
            new GuzzleClientException('', new Request('POST', 'test'), new GuzzleResponse()),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $driver = $this->makeDriver($client);

        $this->expectException(ClientException::class);
        $driver->sendRequest($msg);
    }

    public function testThrowsServerExceptionOn500(): void
    {
        $msg = [
            'message' => 'Just testing',
            'sender' => ['text' => 'Tester'],
            'recipients' => [
                ['phonenr' => '+421911000000'],
            ],
        ];

        $mock = new MockHandler([
            new GuzzleServerException('', new Request('POST', 'test'), new GuzzleResponse()),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $driver = $this->makeDriver($client);

        $this->expectException(ServerException::class);
        $driver->sendRequest($msg);
    }

    public function testThrowsRequestException(): void
    {
        $msg = [
            'message' => 'Just testing',
            'sender' => ['text' => 'Tester'],
            'recipients' => [
                ['phonenr' => '+421911000000'],
            ],
        ];

        $mock = new MockHandler([
            new GuzzleRequestException('', new Request('POST', 'test')),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $driver = $this->makeDriver($client);

        $this->expectException(RequestException::class);
        $driver->sendRequest($msg);
    }

    public function testThrowsConnectException(): void
    {
        $msg = [
            'message' => 'Just testing',
            'sender' => ['text' => 'Tester'],
            'recipients' => [
                ['phonenr' => '+421911000000'],
            ],
        ];

        $mock = new MockHandler([
            new GuzzleConnectException('', new Request('POST', 'test')),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new GuzzleClient(['handler' => $handler]);
        $driver = $this->makeDriver($client);

        $this->expectException(ConnectException::class);
        $driver->sendRequest($msg);
    }

    public function testEndpointCanBeOverriddenInConfig(): void
    {
        $client = new GuzzleClient();
        $config = [
            'apiKey' => 'MY_O2SK_API_KEY',
            'endpoint' => 'https://example.test/o2sk/send_batch',
        ];
        $driver = new O2SK($client, $config);
        self::assertSame('https://example.test/o2sk/send_batch', $driver->getEndpoint());
    }
}
