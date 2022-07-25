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
 * Driver for Twilio.
 */
final class Twilio implements Driver
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
     * Account ID.
     */
    private string $accountId;

    /**
     * API Token.
     */
    private string $apiToken;

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
        if (!array_key_exists('accountId', $config) || !array_key_exists('apiToken', $config)) {
            throw new DriverNotConfiguredException();
        }
        $this->accountId = $config['accountId'];
        $this->apiToken = $config['apiToken'];
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'Twilio';
    }

    /**
     * Get endpoint URL.
     */
    public function getEndpoint(): string
    {
        return "https://api.twilio.com/2010-04-01/Accounts/$this->accountId/Messages.json";
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
            $cleanMessage['To'] = $message['to'];
            $cleanMessage['From'] = $message['from'];
            $cleanMessage['Body'] = $message['content'];
            $this->client->request('POST', $this->getEndpoint(), [
                'form_params' => $cleanMessage,
                'auth' => [
                    $this->accountId,
                    $this->apiToken,
                ],
            ]);
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
