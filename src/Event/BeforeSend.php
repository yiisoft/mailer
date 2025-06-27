<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Yiisoft\Mailer\MessageInterface;

/**
 * `BeforeSend` event is triggered right before sending the message.
 *
 * @see \Yiisoft\Mailer\BaseMailer::send()
 *
 * @api
 */
final class BeforeSend
{
    public bool $preventSendingMessage = false;

    public function __construct(
        public readonly MessageInterface $message,
    ) {
    }
}
