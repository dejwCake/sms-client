<?php

declare(strict_types=1);

namespace DejwCake\SmsClient;

use DejwCake\SmsClient\Contracts\Client as ClientContract;
use DejwCake\SmsClient\Contracts\Driver;

/**
 * SMS client.
 */
final readonly class Client implements ClientContract
{
    /**
     * @param Driver $driver The driver to use.
     */
    public function __construct(public Driver $driver)
    {
    }

    /**
     * Get the driver name.
     */
    public function getDriver(): string
    {
        return $this->driver->getDriver();
    }

    /**
     * Send the message.
     *
     * @param array<string, string> $message The message array.
     */
    public function send(array $message): bool
    {
        return $this->driver->sendRequest($message);
    }
}
