<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Contracts;

/**
 * SMS client.
 */
interface Client
{
    /**
     * Get the driver name.
     */
    public function getDriver(): string;

    /**
     * Send the message.
     *
     * @param array<string, string> $msg The message array.
     */
    public function send(array $msg): bool;
}
