<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Drivers;

use DejwCake\SmsClient\Contracts\Driver;
use GuzzleHttp\ClientInterface as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

/**
 * Null driver for testing.
 */
final readonly class NullDriver implements Driver
{
    /**
     * @param GuzzleClient $client The Guzzle Client instance.
     * @param ResponseInterface $response The response instance.
     */
    public function __construct(protected GuzzleClient $client, protected ResponseInterface $response)
    {
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
