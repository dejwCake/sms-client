<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Contracts;

interface Driver
{
    /**
     * Get driver name.
     */
    public function getDriver(): string;

    /**
     * Get endpoint URL.
     */
    public function getEndpoint(): string;

    /**
     * Send the SMS.
     *
     * @param array<string, string> $message An array containing the message.
     */
    public function sendRequest(array $message): bool;
}
