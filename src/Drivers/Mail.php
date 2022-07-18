<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Contracts\Mailer;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use Throwable;

/**
 * Generic mail driver
 */
final class Mail implements Driver
{
    /**
     * Mailer.
     */
    protected Mailer $mailer;

    /**
     * Endpoint.
     */
    protected string $endpoint;

    /**
     * @param Mailer $mailer The Mailer instance.
     * @param array<string, string> $config The configuration.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(Mailer $mailer, array $config)
    {
        $this->mailer = $mailer;
        if (!array_key_exists('domain', $config)) {
            throw new DriverNotConfiguredException();
        }
        $this->endpoint = $config['domain'];
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'Mail';
    }

    /**
     * Get endpoint domain.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Send the SMS.
     *
     * @param array<string, string> $message An array containing the message.
     */
    public function sendRequest(array $message): bool
    {
        try {
            $recipient = preg_replace('/\s+/', '', $message['to']) . "@" . $this->endpoint;
            $this->mailer->send($recipient, $message['content']);

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
