<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Yiisoft\Mailer\MessageInterface;

/**
 * BeforeSend event is triggered right before sending the message.
 *
 * @see \Yiisoft\Mailer\Mailer::beforeSend()
 */
final class BeforeSend implements StoppableEventInterface
{
    private MessageInterface $message;
    private bool $stopPropagation = false;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    public function stopPropagation(): void
    {
        $this->stopPropagation = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }
}
