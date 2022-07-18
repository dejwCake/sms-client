<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Exceptions;

use Matthewbdaly\SMS\Exceptions\ServerException;
use PhpSpec\ObjectBehavior;

final class ServerExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ServerException::class);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(\Throwable::class);
    }
}
