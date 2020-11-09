<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use Throwable;

/**
 * MessageInterface is the interface that should be implemented by mail message classes.
 *
 * A message represents the settings and content of an email, such as the sender, recipient,
 * subject, body, etc.
 *
 * Messages are sent by a [[\yii\mail\MailerInterface]], like the following,
 *
 * ```php
 * $mailer->compose()
 *     ->setFrom('from@domain.com')
 *     ->setTo($form->email)
 *     ->setSubject($form->subject)
 *     ->setTextBody('Plain text content')
 *     ->setHtmlBody('<b>HTML content</b>')
 *     ->send();
 * ```
 *
 * @see MailerInterface
 */
interface MessageInterface
{
    /**
     * Sets mailer.
     * @param MailerInterface $mailer
     * @return $this self reference.
     */
    public function setMailer(MailerInterface $mailer): self;

    /**
     * Returns the character set of this message.
     * @return string the character set of this message.
     */
    public function getCharset(): string;

    /**
     * Sets the character set of this message.
     * @param string $charset character set name.
     * @return $this self reference.
     */
    public function setCharset(string $charset): self;

    /**
     * Returns the message sender.
     * @return string|array the sender
     */
    public function getFrom();

    /**
     * Sets the message sender.
     * @param string|array $from sender email address.
     * You may pass an array of addresses if this message is from multiple people.
     * You may also specify sender name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setFrom($from): self;

    /**
     * Returns the message recipient(s).
     * @return string|array the message recipients
     */
    public function getTo();

    /**
     * Sets the message recipient(s).
     * @param string|array $to receiver email address.
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setTo($to): self;

    /**
     * Returns the reply-to address of this message.
     * @return string|array the reply-to address of this message.
     */
    public function getReplyTo();

    /**
     * Sets the reply-to address of this message.
     * @param string|array $replyTo the reply-to address.
     * You may pass an array of addresses if this message should be replied to multiple people.
     * You may also specify reply-to name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setReplyTo($replyTo): self;

    /**
     * Returns the Cc (additional copy receiver) addresses of this message.
     * @return string|array the Cc (additional copy receiver) addresses of this message.
     */
    public function getCc();

    /**
     * Sets the Cc (additional copy receiver) addresses of this message.
     * @param string|array $cc copy receiver email address.
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setCc($cc): self;

    /**
     * Returns the Bcc (hidden copy receiver) addresses of this message.
     * @return string|array the Bcc (hidden copy receiver) addresses of this message.
     */
    public function getBcc();

    /**
     * Sets the Bcc (hidden copy receiver) addresses of this message.
     * @param string|array $bcc hidden copy receiver email address.
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setBcc($bcc): self;

    /**
     * Returns the message subject.
     * @return string the message subject
     */
    public function getSubject(): string;

    /**
     * Sets the message subject.
     * @param string $subject message subject
     * @return $this self reference.
     */
    public function setSubject(string $subject): self;

    /**
     * Returns the message text body.
     * @return string the message text body.
     */
    public function getTextBody(): string;

    /**
     * Sets message plain text content.
     * @param string $text message plain text content.
     * @return $this self reference.
     */
    public function setTextBody(string $text): self;

    /**
     * Returns the message HTML body.
     * @return string the message HTML body.
     */
    public function getHtmlBody(): string;

    /**
     * Sets message HTML content.
     * @param string $html message HTML content.
     * @return $this self reference.
     */
    public function setHtmlBody(string $html): self;

    /**
     * Attaches existing file to the email message.
     * @param string $fileName full file name
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @return $this self reference.
     */
    public function attach(string $fileName, array $options = []): self;

    /**
     * Attach specified content as file for the email message.
     * @param string $content attachment file content.
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @return $this self reference.
     */
    public function attachContent(string $content, array $options = []): self;

    /**
     * Attach a file and return it's CID source.
     * This method should be used when embedding images or other data in a message.
     * @param string $fileName file name.
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @return string attachment CID.
     */
    public function embed(string $fileName, array $options = []): string;

    /**
     * Attach a content as file and return it's CID source.
     * This method should be used when embedding images or other data in a message.
     * @param string $content attachment file content.
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @return string attachment CID.
     */
    public function embedContent(string $content, array $options = []): string;

    /**
     * Adds custom header value to the message.
     * Several invocations of this method with the same name will add multiple header values.
     * @param string $name header name.
     * @param string $value header value.
     * @return $this self reference.
     */
    public function addHeader(string $name, string $value): self;

    /**
     * Sets custom header value to the message.
     * @param string $name header name.
     * @param string|array $value header value or values.
     * @return $this self reference.
     */
    public function setHeader(string $name, $value): self;

    /**
     * Returns all values for the specified header.
     * @param string $name header name.
     * @return array header values list.
     */
    public function getHeader(string $name): array;

    /**
     * Sets custom header values to the message.
     * @param array $headers headers in format: `[name => value]`.
     * @return $this self reference.
     */
    public function setHeaders(array $headers): self;

    /**
     * Sends this email message.
     * @throws Throwable throws an exception on send fails.
     */
    public function send(): void;

    /**
     * Returns string representation of this message.
     * @return string the string representation of this message.
     */
    public function toString(): string;

    /**
     * Returns error represents why send fails.
     * @return Throwable
     */
    public function getError(): Throwable;

    /**
     * Sets send fails error.
     * @param Throwable $e
     */
    public function setError(Throwable $e): void;
}
