<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Exceptions\ClientException;
use Matthewbdaly\SMS\Exceptions\ConnectException;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use Matthewbdaly\SMS\Exceptions\RequestException;
use Matthewbdaly\SMS\Exceptions\ServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * Driver for TextLocal
 */
final class TextLocal implements Driver
{
    /**
     * Guzzle client.
     */
    protected GuzzleClient $client;

    /**
     * Guzzle response.
     */
    protected ResponseInterface $response;

    /**
     * Endpoint.
     */
    private string $endpoint = 'https://api.txtlocal.com/send/';

    /**
     * API Key.
     */
    private string $apiKey;

    /**
     * @param GuzzleClient $client The Guzzle Client instance.
     * @param ResponseInterface $response The response instance.
     * @param array<string, string> $config The configuration array.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(GuzzleClient $client, ResponseInterface $response, array $config)
    {
        $this->client = $client;
        $this->response = $response;
        if (!array_key_exists('apiKey', $config)) {
            throw new DriverNotConfiguredException();
        }
        $this->apiKey = $config['apiKey'];
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'TextLocal';
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
            $cleanMessage = [];
            $cleanMessage['apikey'] = urlencode($this->apiKey);
            $cleanMessage['numbers'] = preg_replace('/[^0-9]/', '', $message['to']);
            $cleanMessage['sender'] = urlencode($message['from']);
            $cleanMessage['message'] = rawurlencode($message['content']);
            $this->client->request('POST', $this->getEndpoint() . '?' . http_build_query($cleanMessage));
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
