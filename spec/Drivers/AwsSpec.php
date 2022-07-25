<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Drivers;

use Aws\Sns\SnsClient;
use Matthewbdaly\SMS\Drivers\Aws;
use PhpSpec\ObjectBehavior;

final class AwsSpec extends ObjectBehavior
{
    public function let(SnsClient $sns): void
    {
        $this->beConstructedWith([], $sns);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Aws::class);
    }

    public function it_implements_interface(): void
    {
        $this->shouldImplement('Matthewbdaly\SMS\Contracts\Driver');
    }

    public function it_throws_exception_if_misconfigured(): void
    {
        $config = [
        ];
        $this->beConstructedWith($config);
        $this->shouldThrow('Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException')->during(
            '__construct',
            [$config],
        );
    }

    public function it_returns_the_driver_name(): void
    {
        $this->getDriver()->shouldReturn('Aws');
    }

    public function it_returns_the_driver_endpoint(): void
    {
        $this->getEndpoint()->shouldReturn('');
    }

    public function it_can_be_constructed_with_config_only(): void
    {
        $config = [
            'apiKey' => 'foo',
            'apiSecret' => 'bar',
            'apiRegion' => 'ap-southeast-2',
        ];
        $this->beConstructedWith($config);
        $this->getDriver()->shouldReturn('Aws');
    }

    public function it_sends_the_request(SnsClient $sns): void
    {
        $msg = [
            'to' => '+44 01234 567890',
            'from' => 'Tester',
            'content' => 'Just testing',
        ];
        $args = [
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => $msg['from'],
                ],
            ],
            "SMSType" => "Transactional",
            "Message" => $msg['content'],
            "PhoneNumber" => $msg['to'],
        ];

        $sns->publish($args)->shouldBeCalled();
        $this->beConstructedWith([], $sns);
        $this->sendRequest($msg)->shouldReturn(true);
    }
}
