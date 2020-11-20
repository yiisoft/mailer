<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Yiisoft\Mailer\MessageInterface;

/**
 * BeforeSend event is triggered right before sending the message.
 *
 * @see \Yiisoft\Mailer\BaseMailer::beforeSend()
 * @see stopPropagation() stop sending message.
 */
class BeforeSend extends SendEvent implements StoppableEventInterface
{
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @var bool
     */
    private $stopPropagation = false;

    /**
     * Stops propagation.
     */
    public function stopPropagation(): void
    {
        $this->stopPropagation = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }
}
