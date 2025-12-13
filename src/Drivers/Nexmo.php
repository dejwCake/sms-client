<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Drivers;

use DejwCake\SmsClient\Contracts\Driver;
use DejwCake\SmsClient\Exceptions\ClientException;
use DejwCake\SmsClient\Exceptions\ConnectException;
use DejwCake\SmsClient\Exceptions\DriverNotConfiguredException;
use DejwCake\SmsClient\Exceptions\RequestException;
use DejwCake\SmsClient\Exceptions\ServerException;
use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * Driver for Nexmo.
 */
final readonly class Nexmo implements Driver
{
    private const string ENDPOINT = 'https://rest.nexmo.com/sms/json';

    /**
     * Endpoint.
     */
    private string $endpoint;

    /**
     * API Key.
     */
    private string $apiKey;

    /**
     * API Secret.
     */
    private string $apiSecret;

    /**
     * @param GuzzleClient $client The Guzzle Client instance.
     * @param ResponseInterface $response The response instance.
     * @param array<string, string> $config The configuration array.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(protected GuzzleClient $client, protected ResponseInterface $response, array $config)
    {
        $this->validateConfig($config);
        $this->apiKey = $config['apiKey'];
        $this->apiSecret = $config['apiSecret'];
        $this->endpoint = $config['endpoint'] ?? self::ENDPOINT;
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'Nexmo';
    }

    /**
     * Get endpoint URL.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Send the SMS.
     *
     * @param array<string, string> $message An array containing the message.
     * @throws ServerException  Server exception.
     * @throws RequestException Request exception.
     * @throws ConnectException Connect exception.
     * @throws ClientException  Client exception.
     * @throws GuzzleException
     */
    public function sendRequest(array $message): bool
    {
        try {
            $message['api_key'] = $this->apiKey;
            $message['api_secret'] = $this->apiSecret;
            $message['text'] = $message['content'];
            unset($message['content']);
            $this->client->request('POST', $this->getEndpoint() . '?' . http_build_query($message));
        } catch (GuzzleClientException) {
            throw new ClientException();
        } catch (GuzzleServerException) {
            throw new ServerException();
        } catch (GuzzleConnectException) {
            throw new ConnectException();
        } catch (GuzzleRequestException) {
            throw new RequestException();
        }

        return true;
    }

    /**
     * @param array<string, string> $config
     * @throws DriverNotConfiguredException
     */
    private function validateConfig(array $config): void
    {
        if (!array_key_exists('apiKey', $config) || !array_key_exists('apiSecret', $config)) {
            throw new DriverNotConfiguredException();
        }
    }
}
