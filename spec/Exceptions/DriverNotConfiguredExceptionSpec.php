<?php

declare(strict_types=1);

namespace spec\Matthewbdaly\SMS\Exceptions;

use Matthewbdaly\SMS\Exceptions\DriverNotConfiguredException;
use PhpSpec\ObjectBehavior;

final class DriverNotConfiguredExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DriverNotConfiguredException::class);
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(\Throwable::class);
    }
}
