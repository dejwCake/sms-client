<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Exceptions;

use Matthewbdaly\SMS\Exceptions\ConnectException;
use PhpSpec\ObjectBehavior;

final class ConnectExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ConnectException::class);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(\Throwable::class);
    }
}
