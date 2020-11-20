<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\Event;

use Yiisoft\Mailer\MessageInterface;

/**
 * SendEvent represents the event triggered by [[BaseMailer]].
 *
 * @see \Yiisoft\Mailer\BaseMailer
 */
abstract class SendEvent
{
    /**
     * @var MessageInterface
     */
    protected $message;

    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
