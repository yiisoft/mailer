<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Mail message class that represents the settings and content of an email, such as the sender, recipient, subject,
 * body, etc. Messages are sent by a {@see MailerInterface}.
 */
final class Message implements MessageInterface
{
    /**
     * @var array[]|null
     * @psalm-var array<string,list<string>>|null
     */
    private array|null $headers;

    /**
     * @param string|string[]|null $from The sender email address(es). You may also specify sender name in addition
     * to email address using format: `[email => name]`.
     * @param string|string[]|null $to The receiver email address(es). You may also specify sender name in addition
     * to email address using format: `[email => name]`.
     * @param string|string[]|null $replyTo The reply-to address(es) of this message. You may also specify sender name
     * in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $cc The additional copy receiver address(es) of this message. You may also specify
     * sender name in addition to email address using format: `[email => name]`.
     * @param string|string[]|null $bcc The hidden copy receiver address(es) of this message. You may also specify
     * sender name in addition to email address using format: `[email => name]`.
     * @param File[]|null $attachments The attached files.
     * @param File[]|null $embeddings The embedded files.
     * @param array[]|null $headers The custom headers in format: `[name => value|value[]]`.
     *
     * @psalm-param list<File>|null $attachments
     * @psalm-param list<File>|null $embeddings
     * @psalm-param array<string,string|list<string>>|null $headers
     */
    public function __construct(
        private string|null $charset = null,
        private array|string|null $from = null,
        private array|string|null $to = null,
        private array|string|null $replyTo = null,
        private array|string|null $cc = null,
        private array|string|null $bcc = null,
        private string|null $subject = null,
        private DateTimeImmutable|null $date = null,
        private Priority|null $priority = null,
        private string|null $returnPath = null,
        private string|null $sender = null,
        private string|null $textBody = null,
        private string|null $htmlBody = null,
        private array|null $attachments = null,
        private array|null $embeddings = null,
        array|null $headers = null,
    ) {
        $this->setHeaders($headers);
    }

    public function getCharset(): string|null
    {
        return $this->charset;
    }

    public function withCharset(string|null $charset): MessageInterface
    {
        $new = clone $this;
        $new->charset = $charset;
        return $new;
    }

    public function getFrom(): array|string|null
    {
        return $this->from;
    }

    public function withFrom(array|string|null $from): MessageInterface
    {
        $new = clone $this;
        $new->from = $from;
        return $new;
    }

    public function withAddedFrom(array|string $from): MessageInterface
    {
        return $this->withFrom(
            $this->mergeAddresses($this->from, $from)
        );
    }

    public function getTo(): array|string|null
    {
        return $this->to;
    }

    public function withTo(array|string|null $to): MessageInterface
    {
        $new = clone $this;
        $new->to = $to;
        return $new;
    }

    public function withAddedTo(array|string $to): MessageInterface
    {
        return $this->withTo(
            $this->mergeAddresses($this->to, $to)
        );
    }

    public function getReplyTo(): array|string|null
    {
        return $this->replyTo;
    }

    public function withReplyTo(array|string|null $replyTo): MessageInterface
    {
        $new = clone $this;
        $new->replyTo = $replyTo;
        return $new;
    }

    public function withAddedReplyTo(array|string $replyTo): MessageInterface
    {
        return $this->withReplyTo(
            $this->mergeAddresses($this->replyTo, $replyTo)
        );
    }

    public function getCc(): array|string|null
    {
        return $this->cc;
    }

    public function withCc(array|string|null $cc): MessageInterface
    {
        $new = clone $this;
        $new->cc = $cc;
        return $new;
    }

    public function withAddedCc(array|string $cc): MessageInterface
    {
        return $this->withCc(
            $this->mergeAddresses($this->cc, $cc)
        );
    }

    public function getBcc(): array|string|null
    {
        return $this->bcc;
    }

    public function withBcc(array|string|null $bcc): MessageInterface
    {
        $new = clone $this;
        $new->bcc = $bcc;
        return $new;
    }

    public function withAddedBcc(array|string $bcc): MessageInterface
    {
        return $this->withBcc(
            $this->mergeAddresses($this->bcc, $bcc)
        );
    }

    public function getSubject(): string|null
    {
        return $this->subject;
    }

    public function withSubject(string|null $subject): MessageInterface
    {
        $new = clone $this;
        $new->subject = $subject;
        return $new;
    }

    public function getDate(): DateTimeImmutable|null
    {
        return $this->date;
    }

    public function withDate(DateTimeInterface|null $date): MessageInterface
    {
        $new = clone $this;
        if ($date === null) {
            $new->date = $date;
        } else {
            $new->date = $date instanceof DateTimeImmutable ? $date : DateTimeImmutable::createFromInterface($date);
        }
        return $new;
    }

    public function getPriority(): Priority|null
    {
        return $this->priority;
    }

    public function withPriority(Priority|null $priority): MessageInterface
    {
        $new = clone $this;
        $new->priority = $priority;
        return $new;
    }

    public function getReturnPath(): string|null
    {
        return $this->returnPath;
    }

    public function withReturnPath(string|null $address): MessageInterface
    {
        $new = clone $this;
        $new->returnPath = $address;
        return $new;
    }

    public function getSender(): string|null
    {
        return $this->sender;
    }

    public function withSender(string|null $address): MessageInterface
    {
        $new = clone $this;
        $new->sender = $address;
        return $new;
    }

    public function getHtmlBody(): string|null
    {
        return $this->htmlBody;
    }

    public function withHtmlBody(string|null $html): MessageInterface
    {
        $new = clone $this;
        $new->htmlBody = $html;
        return $new;
    }

    public function getTextBody(): string|null
    {
        return $this->textBody;
    }

    public function withTextBody(string|null $text): MessageInterface
    {
        $new = clone $this;
        $new->textBody = $text;
        return $new;
    }

    public function getAttachments(): array|null
    {
        return $this->attachments;
    }

    /**
     * @no-named-arguments
     */
    public function withAttachments(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->attachments = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedAttachments(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->attachments = array_merge($this->attachments ?? [], $files);
        return $new;
    }

    public function withoutAttachments(): MessageInterface
    {
        $new = clone $this;
        $new->attachments = null;
        return $new;
    }

    public function getEmbeddings(): array|null
    {
        return $this->embeddings;
    }

    /**
     * @no-named-arguments
     */
    public function withEmbeddings(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->embeddings = $files;
        return $new;
    }

    /**
     * @no-named-arguments
     */
    public function withAddedEmbeddings(File ...$files): MessageInterface
    {
        $new = clone $this;
        $new->embeddings = array_merge($this->embeddings ?? [], $files);
        return $new;
    }

    public function withoutEmbeddings(): MessageInterface
    {
        $new = clone $this;
        $new->embeddings = null;
        return $new;
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaders(): array|null
    {
        return $this->headers;
    }

    public function withAddedHeader(string $name, string $value): MessageInterface
    {
        $new = clone $this;
        $new->headers ??= [];
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withHeader(string $name, array|string $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value;
        return $new;
    }

    public function withHeaders(array|null $headers): MessageInterface
    {
        $new = clone $this;
        $new->setHeaders($headers);
        return $new;
    }

    public function __toString(): string
    {
        $result = [];
        if ($this->headers !== null) {
            foreach ($this->headers as $name => $values) {
                foreach ($values as $value) {
                    $result[] = $name . ': ' . $value;
                }
            }
        }
        $result[] = $this->textBody;
        return implode("\n", $result);
    }

    /**
     * @psalm-param array<string,string|list<string>>|null $headers
     */
    private function setHeaders(array|null $headers): void
    {
        $this->headers = HeadersNormalizer::normalize($headers);
    }

    /**
     * @param string|string[]|null $base
     * @param string|string[] $added
     * @return string|string[]
     */
    private function mergeAddresses(array|string|null $base, array|string $added): array|string
    {
        return $base === null
            ? $added
            : array_merge((array) $base, (array) $added);
    }
}
