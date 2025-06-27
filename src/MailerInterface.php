<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

/**
 * `MailerInterface` is the interface that should be implemented by mailers.
 *
 * For example:
 *
 * ```php
 * $message = (new Message())
 *     ->withFrom('from@domain.com')
 *     ->withTo('to@domain.com')
 *     ->withSubject('Message subject')
 *     ->withTextBody('Plain text content')
 *     ->withHtmlBody('<b>HTML content</b>')
 * ;
 * $mailer->send($message);
 * ```
 *
 * @api
 */
interface MailerInterface
{
    /**
     * Sends the given email message.
     *
     * @param MessageInterface $message The email message instance to be sent.
     */
    public function send(MessageInterface $message): void;

    /**
     * Sends multiple messages at once.
     *
     * This method may be implemented by some mailers which support more efficient way of
     * sending multiple messages in the same batch.
     *
     * @param MessageInterface[] $messages List of email messages, which should be sent.
     *
     * @return SendResults The result object that contains all messages and errors for failed sent messages.
     */
    public function sendMultiple(array $messages): SendResults;
}
