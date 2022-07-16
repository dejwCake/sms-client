<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Drivers;

use Aws\Sns\Exception\SnsException;
use Matthewbdaly\SMS\Contracts\Driver;
use Aws\Sns\SnsClient;
use Matthewbdaly\SMS\Exceptions\ClientException;
use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;

/**
 * Driver for AWS SNS.
 */
class Aws implements Driver
{
    /**
     * Guzzle response.
     *
     * @var
     */
    protected $response;

    /**
     * Endpoint.
     *
     * @var
     */
    private $endpoint = '';

    /**
     * SNS Client
     *
     * @var
     */
    protected $sns;

    /**
     * Constructor.
     *
     * @param array          $config The configuration array.
     * @param SnsClient|null $sns    The Amazon SNS client.
     * @return void
     * @throws DriverNotConfiguredException Driver not configured correctly.
     *
     */
    public function __construct(array $config = [], SnsClient $sns = null)
    {
        if (!$sns) {
            if (!array_key_exists('api_key', $config) || !array_key_exists('api_secret', $config) || !array_key_exists(
                'api_region',
                $config
            )) {
                throw new DriverNotConfiguredException();
            }
            $params = [
                'credentials' => [
                    'key' => $config['api_key'],
                    'secret' => $config['api_secret']
                ],
                'region' => $config['api_region'],
                'version' => 'latest'
            ];
            $sns = new SnsClient($params);
        }
        $this->sns = $sns;
    }

    /**
     * Get driver name.
     *
     * @return string
     */
    public function getDriver(): string
    {
        return 'Aws';
    }

    /**
     * Get endpoint URL.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Send the SMS.
     *
     * @param array $message An array containing the message.
     *
     * @return boolean
     * @throws ClientException  Client exception.
     *
     */
    public function sendRequest(array $message): bool
    {
        try {
            $args = [
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
                        'StringValue' => $message['from']
                    ]
                ],
                "SMSType" => "Transactional",
                "Message" => $message['content'],
                "PhoneNumber" => $message['to']
            ];

            $this->sns->publish($args);
        } catch (SnsException $e) {
            throw new ClientException();
        }

        return true;
    }
}
