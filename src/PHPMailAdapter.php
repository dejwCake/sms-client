<?php

declare(strict_types=1);

namespace Matthewbdaly\SMS;

use Matthewbdaly\SMS\Contracts\Mailer;

/**
 * Basic mailer interface implementation
 */
final class PHPMailAdapter implements Mailer
{
    /**
     * Send email
     *
     * @param string $recipient The recipent's email.
     * @param string $message The message.
     */
    public function send(string $recipient, string $message): void
    {
        mail($recipient, "", $message);
    }
}
