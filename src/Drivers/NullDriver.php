<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use GuzzleHttp\ClientInterface as GuzzleClient;
use Matthewbdaly\SMS\Contracts\Driver;
use Psr\Http\Message\ResponseInterface;

/**
 * Null driver for testing.
 */
final class NullDriver implements Driver
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
     * @param GuzzleClient $client The Guzzle Client instance.
     * @param ResponseInterface $response The response instance.
     */
    public function __construct(GuzzleClient $client, ResponseInterface $response)
    {
        $this->client = $client;
        $this->response = $response;
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'Null';
    }

    /**
     * Get endpoint URL.
     */
    public function getEndpoint(): string
    {
        return '';
    }

    /**
     * Send the SMS.
     *
     * @param array<string, string> $message An array containing the message.
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function sendRequest(array $message): bool
    {
        return true;
    }
}
