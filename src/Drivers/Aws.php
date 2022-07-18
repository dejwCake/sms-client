<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use Aws\Sns\Exception\SnsException;
use Aws\Sns\SnsClient;
use Matthewbdaly\SMS\Contracts\Driver;
use Matthewbdaly\SMS\Exceptions\ClientException;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;

/**
 * Driver for AWS SNS.
 */
final class Aws implements Driver
{
    /**
     * SNS Client
     */
    protected SnsClient|null $sns;

    /**
     * @param array<string, string> $config The configuration array.
     * @param SnsClient|null $sns The Amazon SNS client.
     * @throws DriverNotConfiguredException Driver not configured correctly.
     */
    public function __construct(array $config = [], ?SnsClient $sns = null)
    {
        if (!$sns) {
            if (
                !array_key_exists('api_key', $config)
                || !array_key_exists('api_secret', $config)
                || !array_key_exists('api_region', $config)
            ) {
                throw new DriverNotConfiguredException();
            }
            $params = [
                'credentials' => [
                    'key' => $config['api_key'],
                    'secret' => $config['api_secret'],
                ],
                'region' => $config['api_region'],
                'version' => 'latest',
            ];
            $sns = new SnsClient($params);
        }
        $this->sns = $sns;
    }

    /**
     * Get driver name.
     */
    public function getDriver(): string
    {
        return 'Aws';
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
     * @throws ClientException Client exception.
     */
    public function sendRequest(array $message): bool
    {
        try {
            $args = [
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
                        'StringValue' => $message['from'],
                    ],
                ],
                "SMSType" => "Transactional",
                "Message" => $message['content'],
                "PhoneNumber" => $message['to'],
            ];

            $this->sns->publish($args);
        } catch (SnsException) {
            throw new ClientException();
        }

        return true;
    }
}
