<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Yiisoft\Mailer\MessageInterface;

/**
 * `AfterSend` event is triggered right after sent the message.
 *
 * @see \Yiisoft\Mailer\Mailer::afterSend()
 */
final class AfterSend
{
    public function __construct(private MessageInterface $message)
    {
    }

    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
