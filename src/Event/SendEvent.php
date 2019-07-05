<?php
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
     * @var \Yiisoft\Mailer\MessageInterface $message
     */
    protected $message;

    /**
     * Returns message instance.
     *
     * @return \Yiisoft\Mailer\MessageInterface message interface.
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
