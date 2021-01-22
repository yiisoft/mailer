<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * MessageFactoryInterface defines interfaces how to create a mail message instance.
 */
interface MessageFactoryInterface
{
    /**
     * Creates a new message instance.
     *
     * @param MailerInterface $mailer The mailer instance.
     *
     * @return MessageInterface The message instance.
     */
    public function create(MailerInterface $mailer): MessageInterface;
}
