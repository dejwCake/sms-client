<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS;

use Matthewbdaly\SMS\Contracts\Client as ClientContract;
use Matthewbdaly\SMS\Contracts\Driver;

/**
 * SMS client.
 */
final class Client implements ClientContract
{
    /**
     * Driver to use.
     */
    private Driver $driver;

    /**
     * @param Driver $driver The driver to use.
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
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
     * @param array<string, string> $msg The message array.
     */
    public function send(array $msg): bool
    {
        return $this->driver->sendRequest($msg);
    }
}
