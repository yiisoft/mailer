<?php
namespace Yiisoft\Mailer\Event;

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
    public $message;
}
