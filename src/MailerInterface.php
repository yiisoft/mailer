<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Throwable;

/**
 * `MailerInterface` is the interface that should be implemented by mailers.
 *
 * A mailer should support composition of a {@see \Yiisoft\Mailer\MessageInterface} body through the view
 * rendering mechanism and sending one or multiple {@see \Yiisoft\Mailer\MessageInterface}.
 *
 * For example:
 *
 * ```php
 * $message = $mailer->compose()
 *     ->withFrom('from@domain.com')
 *     ->withTo('to@domain.com')
 *     ->withSubject('Message subject')
 *     ->withTextBody('Plain text content')
 *     ->withHtmlBody('<b>HTML content</b>')
 * ;
 * $mailer->send($message);
 * ```
 */
interface MailerInterface
{
    /**
     * Sends the given email message.
     *
     * @param MessageInterface $message The email message instance to be sent.
     *
     * @throws Throwable If sending failed.
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
