<?php
namespace Yiisoft\Mailer\Event;

use Yiisoft\Mailer\MessageInterface;

/**
 * AfterSend event is triggered right after sent the message.
 *
 * @see \Yiisoft\Mailer\BaseMailer::afterSend()
 */
class AfterSend extends SendEvent
{
    /**
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }
}
