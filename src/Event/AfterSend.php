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
     * @var bool $successful whether the message was sent successfully.
     */
    private $successful;

    /**
     * @return bool whether the message was sent successfully.
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * @param MessageInterface $message
     * @param bool $successful
     */
    public function __construct(MessageInterface $message, bool $successful)
    {
        $this->message = $message;
        $this->successful = $successful;
    }
}
