<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS\Tests\Support;

use Aws\Sns\SnsClient;

/**
 * Test double to spy on SnsClient::publish calls.
 */
class SpySnsClient extends SnsClient
{
    private int $publishCalls = 0;

    /** @var array<string, string|int|bool>|null */
    private ?array $lastPublishArgs = null;

    public function __construct()
    {
        // Intentionally not calling parent constructor to avoid AWS config.
    }

    /**
     * Capture publish arguments.
     *
     * @param array<string, string|int|bool> $args
     */
    public function publish(array $args): void
    {
        $this->publishCalls++;
        $this->lastPublishArgs = $args;
    }

    public function getPublishCalls(): int
    {
        return $this->publishCalls;
    }

    public function getLastPublishArgs(): ?array
    {
        return $this->lastPublishArgs;
    }
}
