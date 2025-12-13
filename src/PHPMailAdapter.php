<?php

declare(strict_types=1);

namespace DejwCake\SmsClient;

use DejwCake\SmsClient\Contracts\Mailer;

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
    public function send(string $recipient, string $message): bool
    {
        return mail($recipient, "", $message);
    }
}
