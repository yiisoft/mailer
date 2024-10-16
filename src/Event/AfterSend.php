<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Yiisoft\Mailer\MessageInterface;

/**
 * `AfterSend` event is triggered right after sent the message.
 *
 * @see \Yiisoft\Mailer\BaseMailer::send()
 *
 * @api
 */
final class AfterSend
{
    public function __construct(
        public readonly MessageInterface $message
    ) {
    }
}
