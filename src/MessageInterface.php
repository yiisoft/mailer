<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;
use Stringable;
use Throwable;

/**
 * `MessageInterface` is the interface that should be implemented by mail message classes.
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
interface MessageInterface extends Stringable
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
     */
    public function withCharset(string $charset): self;

    /**
     * Returns the message sender email address.
     *
     * @return string|string[] The sender email address.
     *
     * @see withFrom()
     *
     * @psalm-return array<string, string>|string
     */
    public function getFrom(): array|string;

    /**
     * Returns a new instance with the specified sender email address.
     *
     * @param string|string[] $from The sender email address.
     *
     * You may pass an array of addresses if this message is from multiple people.
     * You may also specify sender name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new sender email address.
     *
     * @psalm-param array<string, string>|string $from
     */
    public function withFrom(array|string $from): self;

    /**
     * Returns the message recipient(s) email address.
     *
     * @return string|string[] The message recipients email address.
     *
     * @see withTo()
     *
     * @psalm-return array<string, string>|string
     */
    public function getTo(): array|string;

    /**
     * Returns a new instance with the specified recipient(s) email address.
     *
     * @param string|string[] $to The receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new recipients email address.
     *
     * @psalm-param array<string, string>|string $to
     */
    public function withTo(array|string $to): self;

    /**
     * Returns the reply-to address of this message.
     *
     * @return string|string[] The reply-to address of this message.
     *
     * @see withReplyTo()
     *
     * @psalm-return array<string, string>|string
     */
    public function getReplyTo(): array|string;

    /**
     * Returns a new instance with the specified reply-to address.
     *
     * @param string|string[] $replyTo The reply-to address.
     *
     * You may pass an array of addresses if this message should be replied to multiple people.
     * You may also specify reply-to name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new reply-to address.
     *
     * @psalm-param array<string, string>|string $replyTo
     */
    public function withReplyTo(array|string $replyTo): self;

    /**
     * Returns the Cc (additional copy receiver) addresses of this message.
     *
     * @return string|string[] The Cc (additional copy receiver) addresses of this message.
     *
     * @see withCc()
     *
     * @psalm-return array<string, string>|string
     */
    public function getCc(): array|string;

    /**
     * Returns a new instance with the specified Cc (additional copy receiver) addresses.
     *
     * @param string|string[] $cc The copy receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new Cc (additional copy receiver) addresses.
     *
     * @psalm-param array<string, string>|string $cc
     */
    public function withCc(array|string $cc): self;

    /**
     * Returns the Bcc (hidden copy receiver) addresses of this message.
     *
     * @return string|string[] The Bcc (hidden copy receiver) addresses of this message.
     *
     * @see withBcc()
     *
     * @psalm-return array<string, string>|string
     */
    public function getBcc(): array|string;

    /**
     * Returns a new instance with the specified Bcc (hidden copy receiver) addresses.
     *
     * @param string|string[] $bcc The hidden copy receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new Bcc (hidden copy receiver) addresses.
     *
     * @psalm-param array<string, string>|string $bcc
     */
    public function withBcc(array|string $bcc): self;

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
     */
    public function withTextBody(string $text): self;

    /**
     * @return File[]
     * @psalm-return list<File>
     */
    public function getAttachments(): array;

    /**
     * Returns a new instance with the specified attached file.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new attached file.
     *
     * @param File $file The file instance.
     */
    public function withAttached(File $file): self;

    /**
     * @return File[]
     * @psalm-return list<File>
     */
    public function getEmbeddedFiles(): array;

    /**
     * Returns a new instance with the specified embedded file.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new embedded file.
     *
     * @param File $file The file instance.
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
     * @psalm-return array<string,list<string>>
     */
    public function getHeaders(): array;

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
     * @psalm-param string|list<string> $value
     */
    public function withHeader(string $name, string|array $value): self;

    /**
     * Returns a new instance with the specified custom header values.
     *
     * @param array $headers The headers in format: `[name => value]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new custom header values.
     *
     * @psalm-param array<string, string|list<string>> $headers
     */
    public function withHeaders(array $headers): self;

    /**
     * Returns error represents why send fails, or null on a successful send.
     */
    public function getError(): ?Throwable;

    /**
     * Returns a new instance with the specified send fails error.
     *
     * @param Throwable $e The send fails error.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new send fails error.
     */
    public function withError(Throwable $e): self;
}
