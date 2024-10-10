<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Yiisoft\Mailer\MessageInterface;

/**
 * `BeforeSend` event is triggered right before sending the message.
 *
 * @see \Yiisoft\Mailer\BaseMailer::beforeSend()
 */
final class BeforeSend implements StoppableEventInterface
{
    private bool $stopPropagation = false;

    public function __construct(
        public readonly MessageInterface $message,
    ) {
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
