<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use Matthewbdaly\SMS\Contracts\Driver;
use Psr\Log\LoggerInterface;

/**
 * Driver for Clockwork.
 */
final class Log implements Driver
{
    /**
     * Logger.
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger The logger instance.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
