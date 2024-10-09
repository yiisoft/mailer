<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * This mailer can be used to avoid send messages.
 */
final class NullMailer implements MailerInterface
{
    public function send(MessageInterface $message): void
    {
    }

    public function sendMultiple(array $messages): SendResults
    {
        return new SendResults(array_values($messages), []);
    }
}
