<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

/**
 * MessageInterface is the interface that should be implemented by mail message classes.
 *
 * A message represents the settings and content of an email, such as the sender, recipient, subject, body, etc.
 *
 * Messages are sent by a {@see \Yiisoft\Mailer\MailerInterface}, like the following:
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
interface MessageInterface
{
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
     * @return array<string, string>|string The sender email address.
     *
     * @see withFrom()
     */
    public function getFrom();

    /**
     * Returns a new instance with the specified sender email address.
     *
     * @param array<string, string>|string|string[] $from The sender email address.
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
     * @return array<string, string>|string The message recipients email address.
     *
     * @see withTo()
     */
    public function getTo();

    /**
     * Returns a new instance with the specified recipient(s) email address.
     *
     * @param array<string, string>|string|string[] $to The receiver email address.
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
     * @return array<string, string>|string The reply-to address of this message.
     *
     * @see withReplyTo()
     */
    public function getReplyTo();

    /**
     * Returns a new instance with the specified reply-to address.
     *
     * @param array<string, string>|string|string[] $replyTo The reply-to address.
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
     * @return array<string, string>|string The Cc (additional copy receiver) addresses of this message.
     *
     * @see withCc()
     */
    public function getCc();

    /**
     * Returns a new instance with the specified Cc (additional copy receiver) addresses.
     *
     * @param array<string, string>|string|string[] $cc The copy receiver email address.
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
     * @return array<string, string>|string The Bcc (hidden copy receiver) addresses of this message.
     *
     * @see withBcc()
     */
    public function getBcc();

    /**
     * Returns a new instance with the specified Bcc (hidden copy receiver) addresses.
     *
     * @param array<string, string>|string|string[] $bcc The hidden copy receiver email address.
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
     * Returns the date when the message was sent, or null if it was not set.
     *
     * @return DateTimeImmutable|null The date when the message was sent.
     */
    public function getDate(): ?DateTimeImmutable;

    /**
     * Returns a new instance with the specified date when the message was sent.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new date when the message was sent.
     *
     * @param DateTimeInterface $date The date when the message was sent.
     *
     * @return self
     */
    public function withDate(DateTimeInterface $date): self;

    /**
     * Returns the priority of this message.
     *
     * @return int The priority value as integer in range: `1..5`,
     * where 1 is the highest priority and 5 is the lowest.
     */
    public function getPriority(): int;

    /**
     * Returns a new instance with the specified priority of this message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message priority.
     *
     * @param int $priority The priority value, should be an integer in range: `1..5`,
     * where 1 is the highest priority and 5 is the lowest.
     *
     * @return self
     */
    public function withPriority(int $priority): self;

    /**
     * Returns the return-path (the bounce address) of this message.
     *
     * @return string The bounce email address.
     */
    public function getReturnPath(): string;

    /**
     * Returns a new instance with the specified return-path (the bounce address) of this message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new return-path (the bounce address).
     *
     * @param string $address The bounce email address.
     *
     * @return self
     */
    public function withReturnPath(string $address): self;

    /**
     * Returns the message actual sender email address.
     *
     * @return string The actual sender email address.
     */
    public function getSender(): string;

    /**
     * Returns a new instance with the specified actual sender email address.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new actual sender email address.
     *
     * @param string $address The actual sender email address.
     *
     * @return self
     */
    public function withSender(string $address): self;

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
     * Returns a new instance with the specified attached file.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new attached file.
     *
     * @param File $file The file instance.
     *
     * @return self
     */
    public function withAttached(File $file): self;

    /**
     * Returns a new instance with the specified embedded file.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new embedded file.
     *
     * @param File $file The file instance.
     *
     * @return self
     */
    public function withEmbedded(File $file): self;

    /**
     * Returns all values for the specified header.
     *
     * @param string $name The header name.
     *
     * @return string[] The header values list.
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
     * @param string|string[] $value The header value or values.
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
     * @param array<string, string|string[]> $headers The headers in format: `[name => value]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new custom header values.
     *
     * @return self
     */
    public function withHeaders(array $headers): self;

    /**
     * Returns error represents why send fails, or null on a successful send.
     *
     * @return Throwable|null
     */
    public function getError(): ?Throwable;

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
     * Returns string representation of this message.
     *
     * @return string The string representation of this message.
     */
    public function __toString(): string;
}
