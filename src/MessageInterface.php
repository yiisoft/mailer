<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;
use Stringable;

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
 *
 * `Stringable` implementation need for debug purposes only. Method `__toString()` should return string representation
 * of message.
 */
interface MessageInterface extends Stringable
{
    /**
     * Returns the charset of this message.
     *
     * @return string|null The charset of this message.
     */
    public function getCharset(): string|null;

    /**
     * Returns a new instance with the specified charset.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new charset.
     *
     * @param string|null $charset The charset name.
     */
    public function withCharset(string|null $charset): self;

    /**
     * Returns the message sender email address.
     *
     * @return string|string[]|null The sender email address.
     *
     * @see withFrom()
     */
    public function getFrom(): array|string|null;

    /**
     * Returns a new instance with the specified sender email address.
     *
     * @param string|string[]|null $from The sender email address.
     *
     * You may pass an array of addresses if this message is from multiple people.
     * You may also specify sender name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new sender email address.
     */
    public function withFrom(array|string|null $from): self;

    /**
     * Returns a new instance with added sender email address(es).
     *
     * @param string|string[] $from The sender email address(es).
     */
    public function withAddedFrom(array|string $from): self;

    /**
     * Returns the message recipient(s) email address.
     *
     * @return string|string[]|null The message recipients email address.
     *
     * @see withTo()
     */
    public function getTo(): array|string|null;

    /**
     * Returns a new instance with the specified recipient(s) email address.
     *
     * @param string|string[]|null $to The receiver email address.
     *
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new recipients email address.
     */
    public function withTo(array|string|null $to): self;

    /**
     * Returns a new instance with added recipient(s) email address.
     *
     * @param string|string[] $to The receiver email address.
     */
    public function withAddedTo(array|string $to): self;

    /**
     * Returns the reply-to address of this message.
     *
     * @return string|string[]|null The reply-to address of this message.
     *
     * @see withReplyTo()
     */
    public function getReplyTo(): array|string|null;

    /**
     * Returns a new instance with the specified reply-to address.
     *
     * @param string|string[]|null $replyTo The reply-to address.
     *
     * You may pass an array of addresses if this message should be replied to multiple people.
     * You may also specify reply-to name in addition to email address using format: `[email => name]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new reply-to address.
     */
    public function withReplyTo(array|string|null $replyTo): self;

    /**
     * Returns a new instance with added reply-to address(es).
     *
     * @param string|string[] $replyTo The reply-to address(es).
     */
    public function withAddedReplyTo(array|string $replyTo): self;

    /**
     * Returns the Cc (additional copy receiver) addresses of this message.
     *
     * @return string|string[]|null The Cc (additional copy receiver) addresses of this message.
     *
     * @see withCc()
     */
    public function getCc(): array|string|null;

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
     */
    public function withCc(array|string $cc): self;

    /**
     * Returns a new instance with the specified Cc (additional copy receiver) address(es).
     *
     * @param string|string[] $cc The copy receiver email address(es).
     */
    public function withAddedCc(array|string $cc): self;

    /**
     * Returns the Bcc (hidden copy receiver) addresses of this message.
     *
     * @return string|string[]|null The Bcc (hidden copy receiver) addresses of this message.
     *
     * @see withBcc()
     */
    public function getBcc(): array|string|null;

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
     */
    public function withBcc(array|string|null $bcc): self;

    /**
     * Returns a new instance with the specified Bcc (hidden copy receiver) address(es).
     *
     * @param string|string[] $bcc The hidden copy receiver email address(es).
     */
    public function withAddedBcc(array|string $bcc): self;

    /**
     * Returns the message subject.
     *
     * @return string|null The message subject.
     */
    public function getSubject(): string|null;

    /**
     * Returns a new instance with the specified message subject.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message subject.
     *
     * @param string|null $subject The message subject.
     */
    public function withSubject(string|null $subject): self;

    /**
     * Returns the date when the message was sent, or null if it was not set.
     *
     * @return DateTimeImmutable|null The date when the message was sent.
     */
    public function getDate(): DateTimeImmutable|null;

    /**
     * Returns a new instance with the specified date when the message was sent.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new date when the message was sent.
     *
     * @param DateTimeInterface|null $date The date when the message was sent.
     */
    public function withDate(DateTimeInterface|null $date): self;

    /**
     * Returns the priority of this message.
     *
     * @return Priority|null The message priority.
     */
    public function getPriority(): Priority|null;

    /**
     * Returns a new instance with the specified priority of this message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message priority.
     *
     * @param Priority|null $priority The message priority.
     */
    public function withPriority(Priority|null $priority): self;

    /**
     * Returns the return-path (the bounce address) of this message.
     *
     * @return string|null The bounce email address.
     */
    public function getReturnPath(): string|null;

    /**
     * Returns a new instance with the specified return-path (the bounce address) of this message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new return-path (the bounce address).
     *
     * @param string|null $address The bounce email address.
     */
    public function withReturnPath(string|null $address): self;

    /**
     * Returns the message actual sender email address.
     *
     * @return string|null The actual sender email address.
     */
    public function getSender(): string|null;

    /**
     * Returns a new instance with the specified actual sender email address.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new actual sender email address.
     *
     * @param string|null $address The actual sender email address.
     */
    public function withSender(string|null $address): self;

    /**
     * Returns the message HTML body.
     *
     * @return string|null The message HTML body.
     */
    public function getHtmlBody(): string|null;

    /**
     * Returns a new instance with the specified message HTML content.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message HTML content.
     *
     * @param string|null $html message HTML content.
     */
    public function withHtmlBody(string|null $html): self;

    /**
     * Returns the message text body.
     *
     * @return string|null The message text body.
     */
    public function getTextBody(): string|null;

    /**
     * Returns a new instance with the specified message plain text content.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new message plain text content.
     *
     * @param string|null $text The message plain text content.
     */
    public function withTextBody(string|null $text): self;

    /**
     * @return File[]|null
     * @psalm-return list<File>|null
     */
    public function getAttachments(): array|null;

    /**
     * Returns a new instance with the specified attached files.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new attached files.
     *
     * @param File ...$files The file instances.
     *
     * @no-named-arguments
     */
    public function withAttachments(File ...$files): self;

    /**
     * Returns a new instance with the specified added attached files.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has added the new attached files.
     *
     * @param File ...$files The file instances.
     *
     * @no-named-arguments
     */
    public function withAddedAttachments(File ...$files): self;

    /**
     * Returns a new instance without attached files.
     */
    public function withoutAttachments(): self;

    /**
     * @return File[]|null
     * @psalm-return list<File>|null
     */
    public function getEmbeddings(): array|null;

    /**
     * Returns a new instance with the specified embedded files.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new embedded file.
     *
     * @param File ...$files The file instances.
     *
     * @no-named-arguments
     */
    public function withEmbeddings(File ...$files): self;

    /**
     * Returns a new instance with the specified added embedded files.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has added the new embedded files.
     *
     * @param File ...$files The file instances.
     *
     * @no-named-arguments
     */
    public function withAddedEmbeddings(File ...$files): self;

    /**
     * Returns a new instance without embedded files.
     */
    public function withoutEmbeddings(): self;

    /**
     * Returns all values for the specified header.
     *
     * @param string $name The header name.
     *
     * @return string[] The header values list.
     */
    public function getHeader(string $name): array;

    /**
     * @psalm-return array<string,list<string>>|null
     */
    public function getHeaders(): array|null;

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
     * @param array|null $headers The headers in format: `[name => value]`.
     *
     * This method MUST be implemented in such a way as to retain the immutability of the message,
     * and MUST return an instance that has the new custom header values.
     *
     * @psalm-param array<string, string|list<string>>|null $headers
     */
    public function withHeaders(array|null $headers): self;
}
