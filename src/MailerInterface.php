<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Throwable;

/**
 * MailerInterface is the interface that should be implemented by mailers.
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
     * Creates a new message instance and optionally composes its body content via view rendering.
     *
     * @param array<string, string>|string|null $view the view to be used for rendering the message body.
     * This can be:
     * - a string, which represents the view name for rendering the HTML body of the email.
     *   In this case, the text body will be generated by applying `strip_tags()` to the HTML body.
     * - an array with 'html' and/or 'text' elements. The 'html' element refers to the view name
     *   for rendering the HTML body, while 'text' element is for rendering the text body. For example,
     *   `['html' => 'contact-html', 'text' => 'contact-text']`.
     * - null, meaning the message instance will be returned without body content.
     * @param array $viewParameters The parameters (name-value pairs)
     * that will be extracted and available in the view file.
     * @param array $layoutParameters The parameters (name-value pairs)
     * that will be extracted and available in the layout file.
     *
     * @return MessageInterface The message instance.
     */
    public function compose($view = null, array $viewParameters = [], array $layoutParameters = []): MessageInterface;

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
     * sending multiple messages in the same batch. It must collects failed messages and store
     * corresponding exceptions by {@see MessageInterface::withError()}, then returns them.
     *
     * @param MessageInterface[] $messages List of email messages, which should be sent.
     *
     * @return MessageInterface[] List of fails messages.
     */
    public function sendMultiple(array $messages): array;

    /**
     * Returns a new instance with the specified message body template instance.
     *
     * @param MessageBodyTemplate $template The message body template instance.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the mailer,
     * and MUST return an instance that has the new message body template instance.
     *
     * @return self The new instance.
     */
    public function withTemplate(MessageBodyTemplate $template): self;
}
