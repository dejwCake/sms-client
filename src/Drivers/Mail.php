<?php

declare(strict_types=1);

namespace DejwCake\SmsClient\Drivers;

use DejwCake\SmsClient\Contracts\Driver;
use DejwCake\SmsClient\Contracts\Mailer;
use DejwCake\SmsClient\Exceptions\DriverNotConfiguredException;
use Throwable;

/**
 * Generic mail driver
 */
final readonly class Mail implements Driver
{
    /**
     * Endpoint.
     */
    private string $endpoint;

    /**
     * @param Mailer $mailer The Mailer instance.
     * @param array<string, string> $config The configuration.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(protected Mailer $mailer, array $config)
    {
        $this->validateConfig($config);
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

    /**
     * @param array<string, string> $config
     * @throws DriverNotConfiguredException
     */
    private function validateConfig(array $config): void
    {
        if (!array_key_exists('domain', $config)) {
            throw new DriverNotConfiguredException();
        }
    }
}
