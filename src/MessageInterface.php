<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Throwable;

/**
 * MessageInterface is the interface that should be implemented by mail message classes.
 *
 * A message represents the settings and content of an email, such as the sender, recipient, subject, body, etc.
 *
 * Messages are sent by a {@see \Yiisoft\Mailer\MailerInterface}, like the following:
 *
 * ```php
 * $mailer->compose()
 *     ->withFrom('from@domain.com')
 *     ->withTo($form->email)
 *     ->withSubject($form->subject)
 *     ->withTextBody('Plain text content')
 *     ->withHtmlBody('<b>HTML content</b>')
 *     ->send();
 * ```
 */
interface MessageInterface
{
    /**
     * Returns a new instance with the specified mailer instance.
     *
     * @param MailerInterface $mailer The mailer instance.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new mailer instance.
     *
     * @return self
     */
    public function withMailer(MailerInterface $mailer): self;

    /**
     * Returns the charset of this message.
     *
     * @return string The charset of this message.
     */
    public function getCharset(): string;

    /**
     * Returns a new instance with the specified charset.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new charset.
     *
     * @param string $charset The charset name.
     *
     * @return self
     */
    public function withCharset(string $charset): self;

    /**
     * Returns the message sender email address.
     *
     * @return array|string The sender email address.
     */
    public function getFrom();

    /**
     * Returns a new instance with the specified sender email address.
     *
     * @param array|string $from The sender email address.
     *
     * You may pass an array of addresses if this message is from multiple people.
     * You may also specify sender name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new sender email address.
     *
     * @return self
     */
    public function withFrom($from): self;

    /**
     * Returns the message recipient(s) email address.
     *
     * @return array|string The message recipients email address.
     */
    public function getTo();

    /**
     * Returns a new instance with the specified recipient(s) email address.
     *
     * @param array|string $to receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new recipients email address.
     *
     * @return self
     */
    public function withTo($to): self;

    /**
     * Returns the reply-to address of this message.
     *
     * @return array|string The reply-to address of this message.
     */
    public function getReplyTo();

    /**
     * Returns a new instance with the specified reply-to address.
     *
     * @param array|string $replyTo The reply-to address.
     *
     * You may pass an array of addresses if this message should be replied to multiple people.
     * You may also specify reply-to name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new reply-to address.
     *
     * @return self
     */
    public function withReplyTo($replyTo): self;

    /**
     * Returns the Cc (additional copy receiver) addresses of this message.
     *
     * @return array|string The Cc (additional copy receiver) addresses of this message.
     */
    public function getCc();

    /**
     * Returns a new instance with the specified Cc (additional copy receiver) addresses.
     *
     * @param array|string $cc The copy receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new Cc (additional copy receiver) addresses.
     *
     * @return self
     */
    public function withCc($cc): self;

    /**
     * Returns the Bcc (hidden copy receiver) addresses of this message.
     *
     * @return array|string the Bcc (hidden copy receiver) addresses of this message.
     */
    public function getBcc();

    /**
     * Returns a new instance with the specified Bcc (hidden copy receiver) addresses.
     *
     * @param array|string $bcc The hidden copy receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new Bcc (hidden copy receiver) addresses.
     *
     * @return self
     */
    public function withBcc($bcc): self;

    /**
     * Returns the message subject.
     *
     * @return string The message subject.
     */
    public function getSubject(): string;

    /**
     * Returns a new instance with the specified message subject.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message subject.
     *
     * @param string $subject The message subject.
     *
     * @return self
     */
    public function withSubject(string $subject): self;

    /**
     * Returns the message text body.
     *
     * @return string The message text body.
     */
    public function getTextBody(): string;

    /**
     * Returns a new instance with the specified message plain text content.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message plain text content.
     *
     * @param string $text The message plain text content.
     *
     * @return self
     */
    public function withTextBody(string $text): self;

    /**
     * Returns the message HTML body.
     *
     * @return string The message HTML body.
     */
    public function getHtmlBody(): string;

    /**
     * Returns a new instance with the specified message HTML content.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message HTML content.
     *
     * @param string $html message HTML content.
     *
     * @return self
     */
    public function withHtmlBody(string $html): self;

    /**
     * Returns a new instance with the specified attached existing file.
     *
     * @param string $fileName The full file name.
     * @param array $options The options for attached file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new attached existing file.
     *
     * @return self
     */
    public function withAttached(string $fileName, array $options = []): self;

    /**
     * Returns a new instance with the specified attached content as file.
     *
     * @param string $content The content of the attached file.
     * @param array $options The options for attached file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new attached content as file.
     *
     * @return self
     */
    public function withAttachedContent(string $content, array $options = []): self;

    /**
     * Attach a file and return it's CID source.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * @param string $fileName The full file name.
     * @param array $options The options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST create an instance that has the new attached existing file.
     *
     * @return string The attachment CID.
     */
    public function embed(string $fileName, array $options = []): string;

    /**
     * Attach a content as file and return it's CID source.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * @param string $content The attachment file content.
     * @param array $options The options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST create an instance that has the new attached content as file.
     *
     * @return string The attachment CID.
     */
    public function embedContent(string $content, array $options = []): string;

    /**
     * Returns all values for the specified header.
     *
     * @param string $name The header name.
     *
     * @return array The header values list.
     */
    public function getHeader(string $name): array;

    /**
     * Returns a new instance with the specified added custom header value.
     *
     * Several invocations of this method with the same name will add multiple header values.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new added custom header value.
     *
     * @return self
     */
    public function withAddedHeader(string $name, string $value): self;

    /**
     * Returns a new instance with the specified custom header value.
     *
     * @param string $name The header name.
     * @param array|string $value The header value or values.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new custom header value.
     *
     * @return self
     */
    public function withHeader(string $name, $value): self;

    /**
     * Returns a new instance with the specified custom header values.
     *
     * @param array $headers The headers in format: `[name => value]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new custom header values.
     *
     * @return self
     */
    public function withHeaders(array $headers): self;

    /**
     * Returns error represents why send fails.
     *
     * @return Throwable
     */
    public function getError(): Throwable;

    /**
     * Returns a new instance with the specified send fails error.
     *
     * @param Throwable $e The send fails error.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new send fails error.
     *
     * @return self
     */
    public function withError(Throwable $e): self;

    /**
     * Sends this email message.
     *
     * @throws Throwable If the send fails.
     */
    public function send(): void;

    /**
     * Returns string representation of this message.
     *
     * @return string The string representation of this message.
     */
    public function __toString(): string;
}
