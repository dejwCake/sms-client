<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\RequestOptions;
use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Exceptions\ClientException;
use Matthewbdaly\SMS\Exceptions\ConnectException;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use Matthewbdaly\SMS\Exceptions\RequestException;
use Matthewbdaly\SMS\Exceptions\ServerException;

/**
 * Class O2SK
 *
 * @documentation https://smstools.sk/downloads/SMSTOOLS-API-dokumentacia.pdf
 * @package Matthewbdaly\SMS\Drivers
 */
final class O2SK implements Driver
{
    /**
     * Guzzle client.
     */
    private GuzzleClient $client;

    /**
     * API Key.
     */
    private string $apiKey;

    /**
     * Endpoint.
     */
    private string $endpoint;

    /**
     * @param GuzzleClient $client The Guzzle Client instance.
     * @param array $config The configuration array.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(GuzzleClient $client, array $config)
    {
        $this->client = $client;
        $config = array_merge([
            'endpoint' => 'https://api-tls12.smstools.sk/3/send_batch',
        ], $config);
        if (!array_key_exists('apiKey', $config)) {
            throw new DriverNotConfiguredException();
        }
        $this->apiKey = $config['apiKey'];
        $this->endpoint = $config['endpoint'];
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'O2SK';
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
     * @param array $message An array containing the message.
     * @throws ClientException  Client exception.
     * @throws ServerException  Server exception.
     * @throws RequestException Request exception.
     * @throws ConnectException Connect exception.
     * @throws GuzzleException
     */
    public function sendRequest(array $message): bool
    {
        try {
            $payload = [
                'auth' => [
                    'apikey' => $this->apiKey,
                ],
                'data' => $message,
            ];

            $this->client->post($this->endpoint, [RequestOptions::JSON => $payload]);
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
}
