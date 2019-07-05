<?php
namespace Yiisoft\Mailer;

/**
 * MessageFactoryInterface defines interfaces how to create a message instance.
 */
interface MessageFactoryInterface
{
    /**
     * Creates a new message instance.
     * 
     * @param MailerInterface $mailer mailer instance.
     * 
     * @return MessageInterface
     */
    public function create(MailerInterface $mailer): MessageInterface;
}
