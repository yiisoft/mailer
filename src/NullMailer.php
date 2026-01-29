<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * This mailer can be used to avoid sending messages.
 *
 * @api
 */
final class NullMailer implements MailerInterface
{
    public function send(MessageInterface $message): void {}

    public function sendMultiple(array $messages): SendResults
    {
        return new SendResults(array_values($messages), []);
    }
}
