<?php


namespace Matthewbdaly\SMS\Drivers;

use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;

/**
 * Class O2SK
 * @documentation https://smstools.sk/downloads/SMSTOOLS-API-dokumentacia.pdf
 * @package Matthewbdaly\SMS\Drivers
 */
class O2SK implements Driver
{
    /**
     * Guzzle client.
     *
     * @var GuzzleClient
     */
    private $client;

    /**
     * API Key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Endpoint.
     *
     * @var string
     */
    private $endpoint;

    /**
     * O2SK constructor.
     * @param GuzzleClient $client The Guzzle Client instance.
     * @param array        $config The configuration array.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(GuzzleClient $client, array $config)
    {
        $this->client = $client;
        $config = array_merge([
            'endpoint' => 'https://api-tls12.smstools.sk/3/send_batch'
        ], $config);
        if (!array_key_exists('apiKey', $config)) {
            throw new DriverNotConfiguredException();
        }
        $this->apiKey = $config['apiKey'];
        $this->endpoint = $config['endpoint'];
    }

    /**
     * Get driver name.
     *
     * @return string
     */
    public function getDriver(): string
    {
        return 'O2SK';
    }

    /**
     * Get endpoint URL.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Send the SMS.
     *
     * @param array $message An array containing the message.
     * @return boolean
     * @throws \Matthewbdaly\SMS\Exceptions\ClientException  Client exception.
     * @throws \Matthewbdaly\SMS\Exceptions\ServerException  Server exception.
     * @throws \Matthewbdaly\SMS\Exceptions\RequestException Request exception.
     * @throws \Matthewbdaly\SMS\Exceptions\ConnectException Connect exception.
     */
    public function sendRequest(array $message): bool
    {
        try {
            $payload = [
                'auth' => [
                    'apikey' => $this->apiKey
                ],
                'data' => $message
            ];

            $this->client->post($this->endpoint, [RequestOptions::JSON => $payload]);
        } catch (ClientException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\ClientException();
        } catch (ServerException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\ServerException();
        } catch (ConnectException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\ConnectException();
        } catch (RequestException $e) {
            throw new \Matthewbdaly\SMS\Exceptions\RequestException();
        }

        return true;
    }
}
