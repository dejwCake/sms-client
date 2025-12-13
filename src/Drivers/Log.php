<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use Matthewbdaly\SMS\Contracts\Driver;
use Psr\Log\LoggerInterface;

/**
 * Driver for Clockwork.
 */
final readonly class Log implements Driver
{
    /**
     * @param LoggerInterface $logger The logger instance.
     */
    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'Log';
    }

    /**
     * Get endpoint URL.
     */
    public function getEndpoint(): string
    {
        return '';
    }

    /**
     * Send the request.
     *
     * @param array<string, string> $message An array containing the message.
     */
    public function sendRequest(array $message): bool
    {
        $this->logger->info('Message sent', $message);

        return true;
    }
}
